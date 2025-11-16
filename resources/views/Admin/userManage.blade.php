<link rel = "icon" href ="/img/logo.jpg" type = "image/x-icon">

<body id="body-pd" style="background: #80808045;">
    @extends('admin.layouts.nav')
    @section('content')
        <style>
            .table-responsive::-webkit-scrollbar {
                display: none;
            }
        </style>
        <div class="container-fluid" style="margin-top:98px">

            <div class="row">
                <div class="col-lg-12">
                    <button class="btn btn-primary float-right btn-sm" data-toggle="modal" data-target="#newUser"><i
                            class="fa fa-plus"></i> New user</button>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="card col-lg-12">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover text-center ">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>UserId</th>
                                        <th style="width:7%">Photo</th>
                                        <th>Username</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone No.</th>
                                        <th>Type</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        @php
                                            $usertype = $user->usertype;
                                        @endphp
                                        @if ($usertype == 0)
                                            @php
                                                $userType = 'User';
                                            @endphp
                                        @else
                                            @php
                                                $userType = 'Admin';
                                            @endphp
                                        @endif
                                        <tr style="font-size: 14px;">
                                            <td>{{ $user->userid }}</td>
                                            <td><img src="/img/profilePic.jpg" alt="image for this user"
                                                    onError="this.src =\'/img/profilePic.jpg\'" width="70px"
                                                    height="80px">
                                            </td>
                                            <td>{{ $user->username }}</td>
                                            <td>
                                                <p>First Name : <b>{{ $user->firstname }}</b></p>
                                                <p>Last Name : <b>{{ $user->lastname }}</b></p>
                                            </td>
                                            <td style="text-align: left;">{{ $user->email }}</td>
                                            <td>{{ $user->phoneno }}</td>
                                            <td>{{ $userType }}</td>
                                            <td class="text-center">
                                                <div class="row mx-auto" style="width:112px">
                                                    @if ($usertype == 1)
                                                        <button class="btn btn-sm btn-primary" data-toggle="modal"
                                                            data-target="#editUser{{ $user->userid }}"
                                                            type="button">Edit</button>
                                                        <button class="btn btn-sm btn-danger" disabled
                                                            style="margin-left:9px;">Delete</button>
                                                    @else
                                                        <form
                                                            action="{{ route('admin.userManageUpdate', ['userid' => $user->userid]) }}"
                                                            method="POST">
                                                            <button class="btn btn-sm btn-primary" data-toggle="modal"
                                                                data-target="#editUser{{ $user->userid }}"
                                                                type="button">Edit</button>
                                                        </form>
                                                        <form
                                                            action="{{ route('admin.userManageDestroy', ['userid' => $user->userid]) }}"
                                                            method="GET">
                                                            <button class="btn btn-sm btn-danger"
                                                                style="margin-left:5px;">Delete</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- newUser Modal -->
        <div class="modal fade" id="newUser" tabindex="-1" role="dialog" aria-labelledby="newUser" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header text-light" style="background-color: #4b5366;">
                        <h5 class="modal-title" id="newUser">Add New User</h5>
                        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.userManageAdd') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <b><label for="username">Username</label></b>
                                <input class="form-control" id="username" name="username"
                                    placeholder="Choose a unique Username" type="text" value="{{ old('username') }}">
                                @error('username')
                                    <span class="alert alert-danger px-3 py-0 rounded-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <b><label for="firstName">First Name:</label></b>
                                    <input type="text" class="form-control" id="firstName" name="firstName"
                                        placeholder="First Name" value="{{ old('firstName') }}">
                                    @error('firstName')
                                        <span class="alert alert-danger px-3 py-0 rounded-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <b><label for="lastName">Last name:</label></b>
                                    <input type="text" class="form-control" id="lastName" name="lastName"
                                        placeholder="Last Name" value="{{ old('lastName') }}">
                                    @error('lastName')
                                        <span class="alert alert-danger px-3 py-0 rounded-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <b><label for="email">Email:</label></b>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="Enter Your Email" value="{{ old('email') }}">
                                @error('email')
                                    <span class="alert alert-danger px-3 py-0 rounded-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group row my-0">
                                <div class="form-group col-md-6">
                                    <b><label for="phone">Phone No:</label></b>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon">+91</span>
                                        </div>
                                        <input type="tel" class="form-control" id="phone" name="phoneNo"
                                            placeholder="Enter Phone No" value="{{ old('phoneNo') }}">
                                    </div>
                                    @error('phoneNo')
                                        <span class="alert alert-danger px-3 py-0 rounded-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <b><label for="userType">Type:</label></b>
                                    <select name="userType" id="userType" class="custom-select browser-default">
                                        <option value="">Select Type</option>
                                        @if (old('UserType') == 1)
                                            <option value="0" selected>User</option>
                                        @else
                                            <option value="0">User</option>
                                        @endif
                                        @if (old('cattype') == 2)
                                            <option value="1" selected>Admin</option>
                                        @else
                                            <option value="1">Admin</option>
                                        @endif
                                    </select>
                                    @error('userType')
                                        <span class="alert alert-danger px-3 py-0 rounded-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <b><label for="password">Password:</label></b>
                                <input class="form-control" id="password" name="password" placeholder="Enter Password"
                                    type="password" data-toggle="password" value="{{ old('password') }}">
                                @error('password')
                                    <span class="alert alert-danger px-3 py-0 rounded-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <b><label for="password1">Renter Password:</label></b>
                                <input class="form-control" id="cpassword" name="cpassword"
                                    placeholder="Renter Password" type="password" data-toggle="password"
                                    value="{{ old('cpassword') }}">
                                @error('cpassword')
                                    <span class="alert alert-danger px-3 py-0 rounded-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" name="createUser" class="btn btn-success">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- edit user model --}}
        @foreach ($users as $user)
            <div class="modal fade" id="editUser{{ $user->userid }}" tabindex="-1" role="dialog"
                aria-labelledby="editUser{{ $user->userid }}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header text-light" style="background-color: #4b5366;">
                            <h5 class="modal-title" id="editUser{{ $user->userid }}">User Id: <b>{{ $user->userid }}</b>
                            </h5>
                            <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="text-left my-2 row" style="border-bottom: 2px solid #dee2e6;">
                                <div class="form-group col-md-8">
                                    <form action="" method="POST" enctype="multipart/form-data">
                                        <b><label for="image">Profile Picture</label></b>
                                        <input type="file" name="userimage" id="userimage"
                                            class="form-control">
                                        <input type="hidden" id="userId" name="userId" value="$Id">
                                        <button type="submit" class="btn btn-success mt-3"
                                            name="updateProfilePhoto">Update
                                            Img</button>
                                    </form>
                                </div>
                                <div class="form-group col-md-4">
                                    <img src="/img/person-1.jpg" alt="Profile Photo" width="100" height="100"
                                        onError="this.src ='/img/profilePic.jpg'">
                                </div>
                            </div>

                            <form action="{{ route('admin.userManageUpdate', ['userid' => $user->userid]) }}"
                                method="post">
                                @csrf
                                @method('put')
                                <div class="form-group">
                                    <b><label for="username">Username</label></b>
                                    @if ($user->usertype == 0)
                                        <input class="form-control" id="username" name="editusername"
                                            value="{{ $user->username }}" type="text">
                                    @else
                                        <input class="form-control" id="username" name="editusername"
                                            value="{{ $user->username }}" type="text" disabled>
                                    @endif
                                    @error('editusername')
                                        <span class="alert alert-danger px-3 py-0 rounded-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <b><label for="firstName">First Name:</label></b>
                                        <input type="text" class="form-control" id="firstName" name="editfirstName"
                                            value="{{ $user->firstname }}">
                                        @error('editfirstName')
                                            <span class="alert alert-danger px-3 py-0 rounded-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <b><label for="lastName">Last name:</label></b>
                                        <input type="text" class="form-control" id="lastName" name="editlastName"
                                            value="{{ $user->lastname }}">
                                        @error('editlastName')
                                            <span class="alert alert-danger px-3 py-0 rounded-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <b><label for="email">Email:</label></b>
                                    @if ($user->usertype == 1)
                                        <input type="email" class="form-control" id="email" name="editemail"
                                            value="{{ $user->email }}">
                                    @else
                                        <input type="email" class="form-control" id="email" name="editemail"
                                            value="{{ $user->email }}" disabled>
                                    @endif
                                    @error('editemail')
                                        <span class="alert alert-danger px-3 py-0 rounded-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group row my-0">
                                    <div class="form-group col-md-6">
                                        <b><label for="phone">Phone No:</label></b>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon">+91</span>
                                            </div>
                                            <input type="tel" class="form-control" id="phone" name="editphoneNo"
                                                value="{{ $user->phoneno }}">
                                        </div>
                                        @error('editphoneNo')
                                            <span class="alert alert-danger px-3 py-0 rounded-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <b><label for="userType">Type:</label></b>
                                        <select name="edituserType" id="userType" class="form-control" disabled>
                                            <option value="">Select Type</option>
                                            @php
                                                $usertype = $user->usertype;
                                            @endphp
                                            @if ($usertype == 0)
                                                <option value="0" selected>User</option>
                                            @else
                                                <option value="0">User</option>
                                            @endif
                                            @if ($usertype == 1)
                                                <option value="1" selected>Admin</option>
                                            @else
                                                <option value="1">Admin</option>
                                            @endif
                                        </select>
                                        @error('edituserType')
                                            <span class="alert alert-danger px-3 py-0 rounded-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <button type="submit" name="editUser" class="btn btn-success">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endsection
</body>
