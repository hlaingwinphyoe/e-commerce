<div class="modal fade" id="user-edit-{{ $user->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel">{{ $user->name }}</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                     
                 </button>
             </div>
             <div class="modal-body">
                 <h4>Change Password</h4>
                 <form action="{{ route('admin.users.change-password', $user->id) }}" method="post">
                     @csrf
                     @method('patch')

                     <div class="form-group">
                         <label for="">New Password</label>
                         <input type="password" name="password" class="form-control form-control-sm" placeholder="Enter New Password">
                     </div>

                     <div class="form-group">
                         <button type="submit" class="btn btn-sm btn-primary">Change</button>
                     </div>
                 </form>
             </div>

         </div>
     </div>
 </div>