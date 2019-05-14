@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{__('Add Products')}}</div>

                <div class="card-body">
                    <div class="alert alert-danger" role="alert" style="display: none;">
                    </div>

                    <form id="addProductForm" action="{{route('create')}}" method="POST">
                        {{csrf_field()}}
                        <div class="form-group">
                          <label for="name">{{__('Product name')}}:</label>
                          <input name="name" type="text" value="{{ old('name')}}" class="form-control" id="name" required>
                        </div>
                        <div class="form-group">
                          <label for="quantity">{{__('Quantity in stock')}}:</label>
                          <input name="quantity" type="number" value="{{ old('quantity')}}" class="form-control" id="quantity" required>
                        </div>
                        <div class="form-group">
                            <label for="price">{{__('Price per item')}}:</label>
                            <input name="price" type="number" step=".01" value="{{ old('price')}}" class="form-control" id="price" required>
                        </div>
                        <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                      </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{__('Add/Edit Products')}}</div>

                <div class="card-body">
                    <table class="table">
                        <thead>
                          <tr>
                          <th>{{__('Product name/Edit')}}</th>
                            <th>{{__('Quantity in stock')}}</th>
                            <th>{{__('Price Per Item')}}</th>
                            <th>{{__('Datetime submitted')}}</th>
                            <th>{{__('Total value number')}}</th>
                            <th>{{__('File')}}</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td><a href="{{route('edit', $product->id)}}">{{$product->name}}</a></td>
                                    <td>{{$product->quantity}}</td>
                                    <td>{{$product->price}}</td>
                                    <td>{{$product->created_at->format('F jS, Y g:i:s a')}}</td>
                                    <td>{{$product->quantity * $product->price}}</td>
                                    <td>
                                        <a href="{{route('download', $product->id)}}">
                                            {{__('Download')}}
                                        </a>
                                    </td>
                                </tr>
                          @endforeach
                        </tbody>
                      </table>
                      {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>

    $('#addProductForm').submit(function(e){
        $dangerAlert = $('.alert-danger');
        $dangerAlert.hide();
        e.preventDefault();
        $.post($(this).attr('action'), $(this).serialize())
        .done(function(data){ 
            alert(data.message);
            location.reload();
            //window.location.href = "http://www.w3schools.com";
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