<!-- resources/views/components/delete-dialog.blade.php -->
<div x-data="{ showConfirm: false }">
    <x-secondary-button @click="showConfirm = true" name="delete_post">Delete</x-secondary-button>
    <div x-show="showConfirm" id="confirmDeleteDialog" class="fixed top-0 left-0 w-full h-full bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-8 mx-4">
        <p class="text-xl mb-6">Are you sure you want to delete this post?</p>
        <div class="flex justify-end">
            <x-danger-button name="confirm_delete" id="confirm_delete">Delete</x-danger-button>
            <x-secondary-button @click="showConfirm = false" name="cancel" class="ml-2">Cancel</x-secondary>
        </div>
      </div>
    </div>
  </div>
