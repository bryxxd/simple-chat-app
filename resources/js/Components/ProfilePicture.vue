<script>
import { Cropper, Preview } from 'vue-advanced-cropper';
import 'vue-advanced-cropper/dist/style.css';
import { AlertDialog, AlertDialogContent, AlertDialogFooter, AlertDialogHeader, AlertDialogTitle, AlertDialogDescription, AlertDialogTrigger, AlertDialogAction, AlertDialogCancel } from '@/Components/ui/alert-dialog';
import { Avatar, AvatarImage } from '@/Components/ui/avatar';
import { Pencil, LoaderCircle } from 'lucide-vue-next';
import { Input, InputError } from '@/Components/ui/input';
import { Button } from '@/Components/ui/button';
import { Label } from '@/Components/ui/label';
import { router } from '@inertiajs/vue3';
import { useFlashMessages } from "@/composables/useFlashMessage";

export default {
    name: "ProfilePicture",
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
            isCroppingFinished: false,
            validationErrors: null,
            result: {
                coordinates: null,
                image: null
            },
            loadingState: {
                validation: false,
                uploading: false
            },
            croppedImageSrc: null,
            croppedCanvas: null,
            originalFileType: null
        }
    },
    computed: {
        userAvatar() {
            return this.showCropper ? this.croppedImageSrc : this.user.avatar;
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
        LoaderCircle,
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
                // Store the original file type for later use
                this.originalFileType = file.type;

                // First, validate the file with server
                this.loadingState.validation = true;
                this.validationErrors = null;
                const formData = new FormData();
                formData.append('image', file);

                router.post(route('settings.profile.picture.validation'), formData, {
                    onSuccess: () => {
                        // Validation passed, proceed to cropper
                        this.validationErrors = null;
                        this.loadingState.validation = false;
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.croppedImageSrc = e.target.result;
                            this.showCropper = true;
                        }
                        reader.readAsDataURL(file);
                    },
                    onError: (errors) => {
                        this.loadingState.validation = false;
                        // Show validation errors
                        this.validationErrors = errors;
                        console.error('Validation errors:', errors);
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
            this.showUploadDialog = true;
            this.isCroppingFinished = false;
            this.croppedImageSrc = null;
            this.croppedCanvas = null;
            this.validationErrors = null;
        },
        handleCancelUpload() {
            this.showUploadDialog = false;
            this.validationErrors = null;
            this.isCroppingFinished = false;
        },
        handleCrop() {
            this.showCropper = false;
            this.isCroppingFinished = true;
            const cropperResult = this.$refs.cropper.getResult();
            this.croppedImageSrc = cropperResult.canvas.toDataURL();
            this.croppedCanvas = cropperResult.canvas;
        },
        handleUpdateAvatar() {
            const canvas = this.croppedCanvas;

            this.loadingState.uploading = true;

            if (canvas) {
                const formData = new FormData();

                canvas.toBlob(blob => {
                    formData.append('image', blob);

                    router.post(route('settings.profile.picture.store'), formData, {
                        onProgress: (progress) => {
                            this.loadingState.uploading = true;
                        },
                        onSuccess: () => {
                            useFlashMessages('Profile picture status')
                            this.loadingState.uploading = false;
                            this.showUploadDialog = false;
                            this.isCroppingFinished = false;
                            this.showCropper = false;
                            this.validationErrors = null;
                        },
                        onError: (errors) => {
                            this.loadingState.uploading = false;
                            this.validationErrors = errors;
                            console.error('Upload errors:', errors);
                        }
                    });
                }, this.originalFileType); // Use the original file type 
            }
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
                    <img v-else class="w-44 h-44 mx-auto object-cover" :src="userAvatar" alt="user avatar">
                </div>
                <AlertDialogDescription v-if="!isCroppingFinished" class="w-full text-center">
                    JPG and PNG up to 5MB
                    <InputError v-if="validationErrors" :message="validationErrors.image" />
                </AlertDialogDescription>
                <AlertDialogFooter class="sm:justify-center">
                    <AlertDialogCancel @click="handleCancelUpload">Cancel</AlertDialogCancel>
                    <AlertDialogAction v-if="isCroppingFinished" @click="handleUpdateAvatar" :disabled="loadingState.uploading">
                        <span v-if="loadingState.uploading">
                            <LoaderCircle class="animate-spin" />
                        </span>
                        <span v-else>Save</span>
                    </AlertDialogAction>
                    <AlertDialogAction v-else class="relative" :disabled="loadingState.validation">
                        <template v-if="loadingState.validation">
                            <LoaderCircle class="animate-spin" />
                        </template>
                        <template v-else>
                            <form ref="uploadForm">
                                <Input type="file" class="absolute top-0 left-0 opacity-0 w-full h-full cursor-pointer"
                                    @change="handleImageUpload" />Upload a photo
                            </form>
                        </template>
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
                    <cropper ref="cropper" class="cropper" :src="userAvatar" :default-size="defaultSize"
                        @change="onChange" />
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