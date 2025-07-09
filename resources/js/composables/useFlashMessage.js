import { usePage } from '@inertiajs/vue3';
import { useToast } from '@/components/ui/toast/use-toast';

export function useFlashMessages(title) {

    const { toast } = useToast();
    const page = usePage();

    const flash = page.props.flash

    if (flash?.success) {
        toast({
            title: title,
            description: flash.success,
            variant: "success",
        })
    }

    if (flash?.info) {
        toast({
            title: title,
            description: flash.info,
            variant: "info"
        })
    }

    if (flash?.error) {
        toast({
            title: title,
            description: flash.error,
            variant: "destructive",
        })
    }
}