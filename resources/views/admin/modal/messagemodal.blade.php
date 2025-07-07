<div class="modal fade" id="messagemodal" tabindex="-1" role="dialog" aria-labelledby="actionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('admin.users.updateStatus') }}" method="POST">
            @csrf
            <input type="hidden" name="user_id" id="message_user_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sent Message</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" id="message_user_name" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" id="message_user_username" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>Upload your Image</label>
                       <input type="file" id="image"  class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="message">Message</label>
                        {{-- <input id="message_input" type="hidden"  name="message">
                        <trix-editor input="message_input" style="height: 20em;"></trix-editor> --}}
                        <textarea name="message" id="editor" class="form-control" rows="30"></textarea>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Sent</button>
                </div>
            </div>
        </form>
    </div>
</div>

