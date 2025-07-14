<script>
import { Cropper, Preview } from 'vue-advanced-cropper';
import 'vue-advanced-cropper/dist/style.css';
import { AlertDialog, AlertDialogContent, AlertDialogFooter, AlertDialogHeader, AlertDialogTitle, AlertDialogDescription, AlertDialogTrigger, AlertDialogAction, AlertDialogCancel } from '@/Components/ui/alert-dialog';
import { Avatar, AvatarImage } from '@/Components/ui/avatar';
import { Pencil } from 'lucide-vue-next';
import { Input, InputError } from '@/Components/ui/input';
import { Progress } from '@/Components/ui/progress';
import { Button } from '@/Components/ui/button';
import { Label } from '@/Components/ui/label';
import { router } from '@inertiajs/vue3'
export default {
    name: "Profile",
    props: {
        user: {
            type: Object,
            required: true
        },
    },
    data() {
        return {
            showUploadDialog: false,
            showCropper: false,
            userAvatar: this.user.avatar,
            newAvatar: null,
            isCroppingFinished: false,
            validationErrors: null,
            result: {
                coordinates: null,
                image: null
            },
        }
    },
    components: {
        Avatar,
        AvatarImage,
        AlertDialog,
        AlertDialogTrigger,
        AlertDialogCancel,
        AlertDialogAction,
        AlertDialogContent,
        AlertDialogFooter,
        AlertDialogHeader,
        AlertDialogTitle,
        AlertDialogDescription,
        Cropper,
        Preview,
        Pencil,
        Progress,
        Input,
        InputError,
        Button,
        Label
    },
    methods: {
        defaultSize() {
            return {
                width: 300,
                height: 300
            }
        },
        handleImageUpload(event) {
            const file = event.target.files[0];

            if (file) {
                // First, validate the file with server
                const formData = new FormData();
                formData.append('avatar', file);

                router.post(route('settings.profile.avatar.validation'), formData, {
                    onSuccess: () => {
                        // Validation passed, proceed to cropper
                        this.validationErrors = null;
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.newAvatar = e.target.result;
                            this.showCropper = true;
                        }
                        reader.readAsDataURL(file);
                    },
                    onError: (errors) => {
                        // Show validation errors
                        this.validationErrors = errors;
                    },

                });
            }
        },
        onChange({ coordinates, image }) {
            this.result = {
                coordinates: coordinates,
                image: image
            }
        },
        handleCancelCropper() {
            this.showCropper = false;
            this.newAvatar = this.user.avatar;
            this.isCroppingFinished = false;
            this.userAvatar = this.user.avatar;
        },
        handleCancelUpload() {
            this.showUploadDialog = false;
            this.userAvatar = this.user.avatar;
            this.validationErrors = null;
            this.isCroppingFinished = false;
        },
        handleCrop() {
            this.showCropper = false;
            this.isCroppingFinished = true;
            this.userAvatar = this.$refs.cropper.getResult().canvas.toDataURL();
        }
    }
}
</script>
<template>
    <div class="relative">
        <Label for="profile-picture" class="block text-sm font-medium">Profile Picture</Label>

        <div class="relative">
            <Avatar class="w-40 h-40 mt-2">
                <AvatarImage :src="user.avatar" />
            </Avatar>
            <Button type="button" class="p-2 absolute left-0 bottom-0" variant="outline"
                @click="showUploadDialog = true">
                <Pencil />
            </Button>
        </div>

        <!-- Upload Dialog -->
        <AlertDialog :open="showUploadDialog" v-if="!showCropper" class="w-full">
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle>
                        Profile Picture
                    </AlertDialogTitle>
                </AlertDialogHeader>
                <div>
                    <preview v-if="isCroppingFinished" class="w-44 h-44 mx-auto" :image="result.image"
                        :coordinates="result.coordinates" />
                    <img v-else class="w-44 h-44 mx-auto" :src="userAvatar" alt="user avatar">
                </div>
                <AlertDialogDescription v-if="!isCroppingFinished" class="w-full text-center">
                    JPG, PNG, GIF up to 5MB
                    <InputError v-if="validationErrors" :message="validationErrors.avatar" />
                </AlertDialogDescription>
                <AlertDialogFooter class="sm:justify-center">
                    <AlertDialogCancel @click="handleCancelUpload">Cancel</AlertDialogCancel>
                    <AlertDialogAction v-if="isCroppingFinished">
                        <Button>Save</Button>
                    </AlertDialogAction>
                    <AlertDialogAction v-else class="relative">
                        <form ref="uploadForm">
                            <Input type="file" class="absolute opacity-0 cursor-pointer"
                                @change="handleImageUpload" />Upload a photo
                        </form>
                    </AlertDialogAction>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>

        <!-- Cropper Dialog -->
        <AlertDialog :open="showCropper" class="w-full">
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle></AlertDialogTitle>
                    <AlertDialogDescription class="w-full">Adjust the crop area to select the part of the image you want
                        to use as your profile picture.</AlertDialogDescription>
                </AlertDialogHeader>
                <div>
                    <cropper class="cropper" :src="newAvatar" :default-size="defaultSize" @change="onChange" />
                </div>
                <AlertDialogFooter class="sm:justify-center">
                    <AlertDialogCancel @click="handleCancelCropper">Cancel</AlertDialogCancel>
                    <AlertDialogAction @click="handleCrop">Ok</AlertDialogAction>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>
    </div>
</template>

<style scoped>
.cropper {
    width: 100%;
    max-width: 462px;
}
</style>