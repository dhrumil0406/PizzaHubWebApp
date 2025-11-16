<link rel = "icon" href ="/img/logo.jpg" type = "image/x-icon">

@if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#addCat').modal('show');
        });
    </script>
@endif
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<body id="body-pd" style="background: #80808045;">
    @extends('admin.layouts.nav')
    @section('content')
        <div class="container-fluid" style="margin-top: 98px" id="cside">
            <div class="row">
                <div class="col-lg-12">
                    <button class="btn btn-primary float-right btn-md mr-4" data-toggle="modal" data-target="#addCat"><i
                            class="fa fa-plus"></i> Add New category</button>
                </div>
            </div>
            <br>
            <div class="col-lg-12">
                <div class="row d-flex justify-content-center">
                    <!-- Table Panel -->
                    @if (count($categories) > 0)
                        <div class="col-md-12 mb-3" id="side">
                            <div class="card" style="border-radius: 12px;">
                                <div class="card-body">
                                    @php
                                        $sort = request('sort');
                                        $order = request('order') === 'asc' ? 'desc' : 'asc';
                                    @endphp
                                    <div class="table-responsive">
                                        <table class="table table-bordered mb-0">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th class="text-center" style="width:8%;">
                                                        <a
                                                            href="?sort=catid&order={{ $sort === 'catid' ? $order : 'asc' }}">
                                                            Cat.Id
                                                            @if ($sort === 'catid')
                                                                {{ request('order') === 'asc' ? '↑' : '↓' }}
                                                            @endif
                                                        </a>
                                                    </th>
                                                    <th class="text-center" style="width:10%;">Img</th>
                                                    <th class="text-center" style="width:60%;">Category Detail</th>
                                                    <th class="text-center" style="width:8%;">
                                                        <a
                                                            href="?sort=cattype&order={{ $sort === 'cattype' ? $order : 'asc' }}">
                                                            Type
                                                            @if ($sort === 'cattype')
                                                                {{ request('order') === 'asc' ? '↑' : '↓' }}
                                                            @endif
                                                        </a>
                                                    </th>
                                                    <th class="text-center" style="width:14%;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($categories as $cat)
                                                    <tr style="font-size: 14px;">
                                                        <td class="text-center"><b>{{ $cat->catid }}</b></td>
                                                        <td><img src="/catimages/{{ $cat->catimage }}"
                                                                alt="image for this Category" width="100px" height="100px"
                                                                style="object-fit: contain;">
                                                        </td>
                                                        <td>
                                                            <p>Name : <b>{{ $cat->catname }}</b></p>
                                                            <p>Description : <b class="truncate">{{ $cat->catdesc }}</b></p>
                                                            @if ($cat->iscombo == 1)
                                                                <p>Type : <b>Combo</b> | Discount :
                                                                    <b>{{ $cat->discount }} %</b>
                                                                </p>
                                                                @if ($cat->discount > 0)
                                                                    <p>Price : <del
                                                                            style="color: #ff0000;"><b>Rs.{{ $cat->comboprice }}/-</b></del>
                                                                        <b><span
                                                                                style="color: green;">Rs.{{ number_format($cat->comboprice - ($cat->comboprice * $cat->discount) / 100, 2) }}/-</span></b>
                                                                    </p>
                                                                @else
                                                                    <p class="text-green">Price :
                                                                        <b>{{ $cat->comboprice }}</b>
                                                                    </p>
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="text-center mt-2">
                                                                @if ($cat->cattype == 1)
                                                                    <img src="/img/veg-mark.jpg" height="37px">
                                                                @else
                                                                    <img src="/img/non-veg-mark.jpg" height="37px">
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="row mx-auto" style="width: 90px;">
                                                                <button class="btn btn-sm btn-primary" type="button"
                                                                    data-toggle="modal"
                                                                    data-target="#updateCat{{ $cat->catid }}"
                                                                    style="width: 40px; height: 40px; border-radius: 8px;">
                                                                    <i class="fas fa-edit"></i></button>
                                                                <form
                                                                    action="{{ route('category.destroyCategory', ['catid' => $cat->catid]) }}"
                                                                    method="get">
                                                                    <button name="removeCategory"
                                                                        class="btn btn-sm btn-danger"
                                                                        style="width: 40px; height: 40px; border-radius: 8px; margin-left: 7px;"><i
                                                                            class="fas fa-trash"></i></button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="mt-3 d-flex justify-content-center">
                                            {{ $categories->links('pagination::bootstrap-4') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-md-8">
                            <div class="card pt-3 pl-4 pr-4" style="border-radius: 12px;">
                                <div class="card-body">
                                    <h2 class="text-center alert alert-danger">No Category Found</h2>
                                </div>
                            </div>
                        </div>
                    @endif
                    <!-- Table Panel -->
                </div>
            </div>
        </div>

        <!-- Modal -->
        @foreach ($categories as $cat)
            <div class="modal fade" id="updateCat{{ $cat->catid }}" tabindex="-1" role="dialog"
                aria-labelledby="updateCat{{ $cat->catid }}" aria-hidden="true" style="width: -webkit-fill-available;">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header text-light" style="background-color: #4b5366;">
                            <h5 class="modal-title" id="updateCat{{ $cat->catid }}">Category Id:
                                <b> {{ $cat->catid }} </b>
                            </h5>
                            <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('category.updateImage', ['catid' => $cat->catid]) }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="text-left my-2 row" style="border-bottom: 2px solid #dee2e6;">
                                    <div class="form-group col-md-8">
                                        <b><label for="image">Image</label></b>
                                        <input type="file" name="catimagee" id="catimage" class="form-control"
                                            onchange="document.getElementById('itemPhoto').src = window.URL.createObjectURL(this.files[0])"
                                            required>
                                        @error('catimagee')
                                            <span class="alert alert-danger px-3 py-0 rounded-sm">{{ $message }}</span>
                                        @enderror
                                        <button type="submit" class="btn btn-success my-1" name="updateCatPhoto">Update
                                            Img</button>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <img src="/catimages/{{ $cat->catimage }}" id="itemPhoto" alt="Category image"
                                            width="100" height="100">
                                    </div>
                                </div>
                            </form>
                            <form action="{{ route('category.updateCategory', ['catid' => $cat->catid]) }}"
                                method="post">
                                @csrf
                                @method('put')
                                <div class="text-left my-2">
                                    <b><label for="name">Category Name</label></b>
                                    <input class="form-control" id="name" name="catnamee"
                                        value="{{ $cat->catname }}" type="text" required>
                                    @error('catnamee')
                                        <span class="alert alert-danger px-3 py-0 rounded-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="text-left my-2">
                                    <b><label for="desc">Description</label></b>
                                    <textarea class="form-control" id="desc" name="catdesce" rows="2">{{ $cat->catdesc }}</textarea>
                                    @error('catdesce')
                                        <span class="alert alert-danger px-3 py-0 rounded-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-success">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        {{-- Add category Modal --}}
        <div class="modal fade" id="addCat" tabindex="-1" role="dialog" aria-labelledby="addCat"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header text-light" style="background-color: #4b5366;">
                        <h5 class="modal-title" id="addCat">Add New Category</h5>
                        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('category.addCategory') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label class="control-label">Category Name: </label>
                                <input type="text" class="form-control" name="catname" value="{{ old('catname') }}">
                                @error('catname')
                                    <span class="alert alert-danger px-3 py-0 rounded-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="control-label">Description: </label>
                                <textarea type="text" class="form-control" name="catdesc">{{ old('catdesc') }}</textarea>
                                @error('catdesc')
                                    <span class="alert alert-danger px-3 py-0 rounded-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="control-label">Type: </label>
                                <select name="cattype" id="" class="form-control">
                                    <option value="">Select Type</option>
                                    @if (old('cattype') == 1)
                                        <option value="1" selected>Veg Pizza</option>
                                    @else
                                        <option value="1">Veg Pizza</option>
                                    @endif
                                    @if (old('cattype') == 2)
                                        <option value="2" selected>Non-Veg Pizza</option>
                                    @else
                                        <option value="2">Non-Veg Pizza</option>
                                    @endif
                                </select>
                                @error('cattype')
                                    <span class="alert alert-danger px-3 py-0 rounded-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="image" class="control-label">Image: </label>
                                <input type="file" name="catimage" id="image" class="form-control">
                                @error('catimage')
                                    <span class="alert alert-danger px-3 py-0 rounded-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-control mb-3">
                                <input type="checkbox" name="iscombo" id="iscombo" value="1">
                                <label for="iscombo" class="control-label">Check if category is combo!</label>
                            </div>
                            <div id="combo-fields" style="display: none;" class="mt-3">
                                <div class="form-group">
                                    <label class="control-label">Price: </label>
                                    <input type="number" class="form-control" name="comboprice"
                                        value="{{ old('comboprice') }}">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Discount: </label>
                                    <input type="number" class="form-control" name="discount"
                                        value="{{ old('discount') }}">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-md btn-primary">
                                Add Category
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <style>
            table.table th a {
                color: #ffffff;
                text-decoration: none;
            }

            .table-responsive::-webkit-scrollbar {
                display: none;
            }

            @media screen and (max-width : 767px) {
                #cside {
                    padding: 0 30px 20px 20px;
                }

                #side {
                    margin: 20px 0;
                }
            }
        </style>
    @endsection

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isComboCheckbox = document.getElementById('iscombo');
            const comboFields = document.getElementById('combo-fields');

            // Initial check (for old values when form reloads with validation errors)
            if (isComboCheckbox.checked) {
                comboFields.style.display = 'block';
            }

            // Add listener
            isComboCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    comboFields.style.display = 'block';
                } else {
                    comboFields.style.display = 'none';
                }
            });
        });
    </script>
</body>
