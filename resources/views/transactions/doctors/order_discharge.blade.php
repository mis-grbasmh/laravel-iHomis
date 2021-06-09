<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-8">
                <h4 class="mb-0">Discharge Order</h4>
            </div>
            <div class="col-4 text-right">
                <a data-toggle="modal" href="#"  data-target="#newlaborder"  data-backdrop="static" class="btn btn-primary btn-sm">New Discharge Order</i></a>
            </div>
        </div>
        <div class="card-body "><hr/>
            @if (count($errors) > 0)
                 @include('alerts.error') 
            @endif
            <div class="table-responsive">
                <table id="dischargeTable" class="display" cellspacing="0" style="width:100%" >
                    <thead class=" text-primary">
                        <th scope="col">Date Requested</th>
                        <th scope="col">Start Date</th>
                        <th scope="col">Breakfast</th>
                        <th scope="col">Lunch</th>
                        <th scope="col">Dinner</th>
                        <th scope="col">Actions</th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>