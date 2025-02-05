<div class="card-body">
    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="ModalLead" tabindex="-1" role="dialog" aria-labelledby="ModalLeadTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLeadTitle">New Lead</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        @csrf
                        
                        <div class="card card-body">
                            <div class="row">
                                <label for="InputWebSite">Customer information</label>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-12">
                                    <label for="companies_id">Company</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-building"></i></span>
                                        </div>
                                        <select class="form-control" wire:model="companies_id" name="companies_id" id="companies_id">
                                            <option value="">Select company</option>
                                            @forelse ($CompanieSelect as $item)
                                            <option value="{{ $item->id }}">{{ $item->code }} - {{ $item->label }}</option>
                                            @empty
                                            <option value="">No company, please add</option>
                                            @endforelse
                                        </select>
                                    </div>
                                    @error('companies_id') <span class="text-danger">{{ $message }}<br/></span>@enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="companies_addresses_id">Address</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-map-marked-alt"></i></span>
                                        </div>
                                        <select class="form-control" wire:model="companies_addresses_id"  name="companies_addresses_id" id="companies_addresses_id">
                                            @forelse ($AddressSelect as $item)
                                            <option value="{{ $item->id }}">{{ $item->label }} - {{ $item->adress }}</option>
                                            @empty
                                            <option value="">Select address</option>
                                            <option value="">No contact, please add</option>
                                        @endforelse
                                        </select>
                                    </div>
                                    @error('companies_addresses_id') <span class="text-danger">{{ $message }}<br/></span>@enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="companies_contacts_id">Contact</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        </div>
                                        <select class="form-control" wire:model="companies_contacts_id" name="companies_contacts_id" id="companies_contacts_id">
                                            @forelse ($ContactSelect as $item)
                                            <option value="{{ $item->id }}">{{ $item->first_name }} - {{ $item->name }}</option>
                                            @empty
                                            <option value="">Select address</option>
                                            <option value="">No contact, please add</option>
                                            @endforelse
                                        </select>
                                    </div>
                                    @error('companies_contacts_id') <span class="text-danger">{{ $message }}<br/></span>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="card card-body">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="source">Source</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-external-link-square-alt"></i></span>
                                        </div>
                                        <input type="text" class="form-control" wire:model="source"  name="source" id="source" placeholder="Source" >
                                    </div>
                                    @error('source') <span class="text-danger">{{ $message }}<br/></span>@enderror
                                </div>
                                
                                <div class="form-group col-md-6">
                                    <label for="user_id">User management</label>
                                    
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        </div>
                                        <select class="form-control" wire:model="user_id" name="user_id" id="user_id">
                                            <option value="">Select user management</option>
                                        @foreach ($UsersSelect as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    @error('user_id') <span class="text-danger">{{ $message }}<br/></span>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="card card-body">
                            <div class="row">
                                <div class="col-12">
                                    <label>Comment</label>
                                    <textarea class="form-control" rows="3" wire:model="comment" name="comment"  placeholder="..."></textarea>
                                    @error('comment') <span class="text-danger">{{ $message }}<br/></span>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="Submit" wire:click.prevent="storeLead()" class="btn btn-danger btn-flat"><i class="fas fa-lg fa-save"></i> Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->
    <div class="card">
        <div class="table-responsive p-0">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th></th>
                        <th>Company</th>
                        <th>Contact</th>
                        <th>Adress</th>
                        <th>User</th>
                        <th>Source</th>
                        <th>
                            <a class="btn btn-secondary" wire:click.prevent="sortBy('statu')" role="button" href="#">Status @include('include.sort-icon', ['field' => 'statu'])</a>
                        </th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($Leadslist as $Leads)
                    <tr>
                        <td>#{{ $Leads->id }}</td>
                        <td><x-CompanieButton id="{{ $Leads->companies_id }}" label="{{ $Leads->companie['label'] }}"  /></td>
                        <td>{{ $Leads->companie['first_name'] }} {{ $Leads->contact['name'] }}</td>
                        <td>{{ $Leads->adresse['adress'] }} {{ $Leads->adresse['zipcode'] }}  {{ $Leads->adresse['city'] }}</td>
                        <td>{{ $Leads->UserManagement['name'] }}</td>
                        <td>{{ $Leads->source }}</td>
                        <td>
                            @if(1 == $Leads->statu )  <span class="badge badge-info">New</span>@endif
                            @if(2 == $Leads->statu )  <span class="badge badge-warning">Assigned</span>@endif
                            @if(3 == $Leads->statu )  <span class="badge badge-primary">In progress</span>@endif
                            @if(4 == $Leads->statu )  <span class="badge badge-success">Converted</span>@endif
                            @if(5 == $Leads->statu )  <span class="badge badge-danger">Lost</span>@endif
                        </td>
                        <td> 
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                <div class="dropdown-menu">
                                    <a href="#" class="dropdown-item" wire:click="inProgress({{$Leads->id}})"><span class="text-primary"><i class="fa fa-lg fa-fw  fa-edit"></i>In progess</span></a>
                                    <a href="#" class="dropdown-item " wire:click="convertToQuote({{$Leads->id}})" ><span class="text-info"><i class="fa fa-light fa-fw  fa-copy"></i>Convert to quote</span></a>
                                    <a href="#" class="dropdown-item" wire:click="lost({{$Leads->id}})" ><span class="text-danger"><i class="fa fa-lg fa-fw fa-trash"></i> Lost</span></a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                        <x-EmptyDataLine col="8" text=" No data available in table"  />
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th></th>
                        <th>Company</th>
                        <th>Contact</th>
                        <th>Adress</th>
                        <th>User</th>
                        <th>Source</th>
                        <th>Statu</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!-- /.row -->
        {{ $Leadslist->links() }}
    <!-- /.card -->
    </div>
<!-- /.card-body -->
</div>