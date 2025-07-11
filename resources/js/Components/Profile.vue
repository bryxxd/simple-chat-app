<script>
import { Cropper } from 'vue-advanced-cropper';
import 'vue-advanced-cropper/dist/style.css';
import { AlertDialog, AlertDialogContent, AlertDialogFooter, AlertDialogHeader, AlertDialogTitle, AlertDialogDescription, AlertDialogTrigger, AlertDialogAction, AlertDialogCancel } from '@/Components/ui/alert-dialog';
import { Avatar, AvatarImage } from '@/Components/ui/avatar';
import { Pencil } from 'lucide-vue-next';
import { Input } from '@/Components/ui/input';
import { Popover, PopoverContent, PopoverTrigger } from '@/Components/ui/popover';
import { Button } from '@/Components/ui/button';
import { Label } from '@/Components/ui/label';
export default {
    name: "Profile",
    data() {
        return {
            hasUploaded: false,
            image: null,
        }
    },
    props: {
        user: {
            type: Object,
            required: true
        },
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
        Pencil,
        Input,
        Popover,
        PopoverContent,
        PopoverTrigger,
        Button,
        Label
    },
    methods: {
        handleFileUpload(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.image = e.target.result;
                }

                this.hasUploaded = true
                reader.readAsDataURL(file);
            }
        },
        change({ coordinates, canvas }) {
            console.log(coordinates, canvas);
        },
    }
}
</script>
<template>
    <div class="relative">
        <Label for="profile-picture" class="block text-sm font-medium">Profile Picture</Label>

        <Avatar class="w-40 h-40 mt-2">
            <AvatarImage :src="user.avatar" />
        </Avatar>
        <Popover>
            <PopoverTrigger class="absolute bottom-0 left-0" as-child>
                <Button class="p-2" variant="outline">
                    <Pencil />
                </Button>
            </PopoverTrigger>
            <PopoverContent class="w-auto p-0">
                <div class="hover:bg-sidebar-accent hover:text-sidebar-accent-foreground cursor-pointer py-2 px-4">
                    <Label for="profile-picture" class="block w-full cursor-pointer">Upload a photo</Label>
                    <Input type="file" id="profile-picture" name="profile-picture"
                        class="opacity-0 absolute cursor-pointer hidden" accept="image/*" @change="handleFileUpload" />
                </div>
            </PopoverContent>
        </Popover>

        <AlertDialog :open="hasUploaded" class="w-full">
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle>Are you absolutely sure?</AlertDialogTitle>
                    <AlertDialogDescription class="w-full">
                        <cropper class="cropper" :src="image" @change="change" />
                    </AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <AlertDialogCancel>Cancel</AlertDialogCancel>
                    <AlertDialogAction>Continue</AlertDialogAction>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>
    </div>
</template>

<style scoped>
.cropper {
    height: auto;
    max-width: 600px;
    width: 100%;
}
</style>