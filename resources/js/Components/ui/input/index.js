import { cva } from "class-variance-authority";
export { default as Input } from "./Input.vue";
export { default as InputStatusMessage } from "./InputStatusMessage.vue";

export const inputMessageVariant = cva("text-sm", {
    variants: {
        variant: {
            default: "text-red-500",
            success: "text-green-700",
        },
    },
    defaultVariants: {
        variant: "default",
    },
});
