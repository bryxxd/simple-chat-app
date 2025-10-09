import { onMounted, ref } from 'vue';

export function useActiveRoom() {
    const selectedUserId = ref(null);

    function updateSelectedUser(userID) {
        if (selectedUserId.value === userID) return;
        selectedUserId.value = userID;
        setCookieActiveRoom(userID);
    }

    function setCookieActiveRoom(roomId) {
        document.cookie = `activeRoom=${roomId}; path=/; max-age=86400`; // 1 day
    }

    function getCookieActiveRoom() { 
        const match = document.cookie.match(new RegExp('(^| )activeRoom=([^;]+)'));
        return match ? match[2] : null;
    }

    onMounted(() => { 
        // On mount, read the cookie to set the initial active room
        const cookieRoomId = getCookieActiveRoom();
        if (cookieRoomId) {
            // Convert to number if it's a numeric string to match user IDs
            const roomId = isNaN(cookieRoomId) ? cookieRoomId : parseInt(cookieRoomId);
            selectedUserId.value = roomId;
        }
    });

    return {
        selectedUserId,
        updateSelectedUser,
        setCookieActiveRoom
    };
}