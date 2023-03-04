<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="editModalLabel">Edit Address</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
            <form id="modalEdit" class="form-st form-address" action="{{ route('addresses.update',$address->id)}}">
             {{ csrf_field() }}
            {{ method_field('PATCH')}}
      
                <div class="alert alert-danger print-error-msg" style="display:none">
                    <ul></ul>
                </div>
                <div class="col-md-6">
                    <label for="address_name" class="form-label">Address Name*</label>
                    <input type="text" id="address_name" name="address_name" class="form-control" placeholder="Name"value={{ $address->address_name }}>
                </div>
      
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="country_id" class="form-label">{{__('cp.country')}}</label> 
                        <select class="form-control form-control-solid"
                               id="country_id" name="country_id" required>
                            <option value=""> @lang('cp.select') </option>
                        @foreach($countries as $country)
                                <option value="{{$country->id}}" {{$address->country_id==$country->id ? 'selected':''}}> {{$country->country_name}} </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="city_id" class="form-label">{{__('cp.city')}}</label> 
                        <select class="form-control form-control-solid"
                                id="city_id"
                                name="city_id" required>
                            <option value=""> @lang('cp.select') </option>
                        @foreach($cities as $city)
                                <option value="{{$city->id}}" {{$address->city_id==$city->id ? 'selected':''}}> {{$city->city_name}} </option>
                            @endforeach
                        </select>
                    </div>
                </div>
              <div class="mb-3">
                  <label for="note" class="form-label">Notes(Optional)</label>
                  <textarea name="note" class="form-control" id="note" value="{{ $address->note }}" ></textarea>
              </div>
         
                <div class="mb-3 text-center">
                    <button class="btn btn-success btn-submit">Edit Address</button>
                </div>
        
            </form>
          </div>
        </div>
    </div>
</div>

    