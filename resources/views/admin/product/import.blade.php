@extends('admin.includes.main')

@section('main')
    <div class="wraper container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Import / Export Products</h4>
                    </div>
                    <div class="panel-body">
                        <form action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data" class="mb-3">
                            @csrf
                            <div class="form-group">
                                <label for="csv_file">Import CSV File</label>
                                <input type="file" name="csv_file" id="csv_file" class="form-control-file" accept=".csv" required>
                            </div>
                            <button type="submit" class="btn btn-primary mt-2">Import Products</button>
                        </form>
                    </div>
                    <div class="panel-body">
                        <div class="mt-3">
                            <a href="{{ route('admin.sample.csv') }}" class="btn btn-info">Download Sample CSV</a>
                            <a href="{{ route('admin.export.categories') }}" class="btn btn-success">Export Categories</a>
                            <a href="{{ route('admin.export.subcategories') }}" class="btn btn-primary">Export Subcategories</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

