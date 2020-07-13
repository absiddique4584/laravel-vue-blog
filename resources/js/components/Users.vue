<template>
    <div>
        <section class="content">
            <br>
            <div class="row  mt-5" v-if="$gate.isAdmin()">
                <div class="col-sm-10 ">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Manage Users</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-success"  @click="newModal">
                                    Add User <i class="fas fa-user-plus"></i>
                                </button>

                            </div>
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">ID</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Email</th>
                                        <th class="text-center">Type</th>
                                        <th class="text-center">Register Date</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="user in users" :key="user.id">
                                        <td class="text-center">{{user.id}}</td>
                                        <td class="text-center">{{user.name}}</td>
                                        <td class="text-center">{{user.email}}</td>
                                        <td class="text-center">{{user.type | uptext}}</td>
                                        <td class="text-center">{{user.created_at | myDate}}</td>
                                        <td>
                                            <a href="#" @click="editModal(user)">
                                                <i class="fa fa-edit blue" style="color: #23923d;"></i>
                                            </a>
                                            /
                                            <a href="#" @click="deleteUser(user.id)">
                                                <i class="fa fa-trash red" style="color: #e3342f;"></i>
                                            </a>

                                        </td>
                                    </tr>
                                </tbody>


                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>

                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>



        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="editMode" id="exampleModalLabel">Update User</h5>
                        <h5 class="modal-title" v-show="!editMode" id="exampleModalLabel">Add New-User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form @submit.prevent="editMode ? updateUser() :createUser()" >
                    <div class="modal-body">

                        <div class="form-group">
                            <label>Name</label>
                            <input v-model="form.name" type="text" name="name"  placeholder="Write Your Name"
                                   class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                            <has-error :form="form" field="name"></has-error>
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input v-model="form.email" type="email" name="email"  placeholder="Write Your Email"
                                   class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                            <has-error :form="form" field="email"></has-error>
                        </div>


                        <div class="form-group">
                            <label>Biodata(Optional)</label>
                            <input v-model="form.bio" type="text" name="bio"  placeholder="Write Your Bio"
                                   class="form-control" :class="{ 'is-invalid': form.errors.has('bio') }">
                            <has-error :form="form" field="bio"></has-error>
                        </div>


                        <div class="form-group">
                            <label>Select Role</label>
                            <select name="type" id="type" v-model="form.type" class="form-control"
                                    :class="{ 'is-invalid': form.errors.has('type') }" >
                                <option value="">Select User Role</option>
                                <option value="admin">Admin</option>
                                <option value="user">Standard User</option>
                                <option value="author">Author</option>
                            </select>

                        </div>


                        <div class="form-group">
                            <label>Password</label>
                            <input v-model="form.password" type="password" name="password" placeholder="Write Your Password"
                                   class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                            <has-error :form="form" field="password"></has-error>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button v-show="editMode" type="submit" class="btn btn-success">Update</button>
                        <button v-show="!editMode" type="submit" class="btn btn-primary">Add User</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Users",
        data(){
            return{
                editMode: false,
                users: {},
                form: new Form({
                    id: '',
                    name: '',
                    email: '',
                    password: '',
                    type: '',
                    bio: '',
                    photo:''
                })
            }
        },
        methods:{
            updateUser(){
                this.$Progress.start();
                this.form.put('api/user/'+this.form.id)
                .then(()=>{
                    $('#exampleModal').modal('hide')
                    Swal.fire(
                        'Updated',
                        'User Information Has Been Updated Successfully!.',
                        'success'
                    )
                    this.$Progress.finish();
                    Fire.$emit('AfterCreated');
                })
                .catch(()=>{
                    this.$Progress.fail();
                });
            },
            editModal(user){
                this.editMode = true;
                this.form.reset();
                $('#exampleModal').modal('show')
                this.form.fill(user);
            },
            newModal(){
                this.editMode = false;
                this.form.reset();
                $('#exampleModal').modal('show')
            },

            deleteUser(id){
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {

                    // Send request to the server
                    if (result.value) {
                        this.form.delete('api/user/'+id).then(()=>{
                            Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            )
                            Fire.$emit('AfterCreated');
                        }).catch(()=> {
                            Swal.fire("Failed!", "There was something wronge.", "warning");
                        });
                    }
                })
            },
            createUser(){
                this.$Progress.start();

                this.form.post('api/user')
                    .then(()=>{
                    Fire.$emit('AfterCreated');
                    $('#exampleModal').modal('hide')

                    Toast.fire({
                        icon: 'success',
                        title: 'User Created in successfully'
                    })

                    this.$Progress.finish();
                })
                    .catch(()=>{

                    })

            },
            loadUsers(){
                if (this.$gate.isAdmin()){
                    axios.get('api/user')
                        .then(({data})=>(this.users = data.data));
                }

            }
        },
        created() {
            this.loadUsers();
            Fire.$on('AfterCreated',()=>{
                this.loadUsers();
            });
            //setInterval(() => this.loadUsers(),3000);
        }
    }
</script>

<style scoped>

</style>
