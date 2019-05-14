@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{__('Edit Product')}}</div>

                <div class="card-body">
                        <div class="alert alert-danger" role="alert" style="display: none;">
                            {{ session('status') }}
                        </div>

                    <form id="editProductForm" action="{{route('edit', $product->id)}}" method="POST">
                        {{csrf_field()}}
                        {{ method_field('PUT') }}
                        <div class="form-group">
                          <label for="name">{{__('Product name')}}:</label>
                          <input name="name" type="text" value="{{ old('name') ?? $product->name}}" class="form-control" id="name" required>
                        </div>
                        <div class="form-group">
                          <label for="quantity">{{__('Quantity in stock')}}:</label>
                          <input name="quantity" type="number" value="{{ old('quantity') ?? $product->quantity}}" class="form-control" id="quantity" required>
                        </div>
                        <div class="form-group">
                            <label for="price">{{__('Price per item')}}:</label>
                            <input name="price" type="number" step=".01" value="{{ old('price') ?? $product->price}}" class="form-control" id="price" required>
                        </div>
                        <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                      </form>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $('#editProductForm').submit(function(e){
        $dangerAlert = $('.alert-danger');
        $dangerAlert.hide();
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: 'PUT',
            data: $(this).serialize()
        })
        .done(function(data){ 
            alert(data.message);
            window.location.href = "{{route('home')}}";
        })
        .fail(function(xhr, status, error) {
            console.log(error);
            console.log(xhr);
            alert(error);
            var errorString = '<ul>';
            $.each( xhr.responseJSON.errors, function( key, value) {
                errorString += '<li>' + value + '</li>';
            });
            errorString += '</ul>';
            $dangerAlert.html(errorString );
            $dangerAlert.show();
        });
    });
</script>
@endsection