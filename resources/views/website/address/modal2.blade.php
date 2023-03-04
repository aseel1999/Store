<div class="modal fade" id="modalAdderss" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <button type="button" class="closeModal" data-bs-dismiss="modal" aria-label="Close">
              <i class="fa-solid fa-xmark"></i>
          </button>
          <div class="modal-body ms-succ">
              <h5>Add Address</h5>
              <form class="form-st form-address" action="{{ route('addresses.store') }}" method="post">
                {{ csrf_field() }}
                  <div class="form-group">
                    <label for="address_name" class="form-label">Address Name*</label>
                    <input type="text" id="address_name" name="address_name" class="form-control" placeholder="Name" required="">
                  </div>
                  <div class="form-group selectBt">
                    <label for="country_id" class="form-label">Country*</label>
                    <select class="form-control form-control-solid"
                             name="country_id" id="country_id" required>
                      <option value=""> @lang('cp.select')</option>
                          @foreach($countries as $country)
                            <option value="{{$country->id}}"
                               data-id="{{$country->id}}"> {{@$country->country_name}} </option>
                           @endforeach
                     </select>
                  </div>
                  <div class="form-group">
                    <label for="city_id" class="form-label"> City*</label>
                    <select class="form-control form-control-solid"
                         name="city_id" id="city_id" required>
                            <option value=""> @lang('cp.select')</option>
                              @foreach($cities as $city)
                                <option value="{{$city->id}}"
                                       data-id="{{$city->id}}"> {{@$city->city_name}} </option>
                              @endforeach
                         </select>
                  </div>
                  <div class="form-group">
                    <label for="building" class="form-label">Block,Street,Building*</label>
                    <input type="text" id="building" name="building" class="form-control" placeholder="Enter here" required="">
                  </div>
                  <div class="form-group">
                    <label for="note" class="form-label">Notes(Optional)</label>
                    <textarea name="note" class="form-control" id="note"></textarea>
                  </div>
                  <div class="form-group">
                      <button class="btn-site btn-submit"><span>Add Address</span></button>
                  </div>
              </form>
          </div>
      </div>
  </div>
</div>
