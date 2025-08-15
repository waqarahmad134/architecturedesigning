
@extends('admin.layout.app')
@section('admin_content')

<div id="archi-home" class="archi-tab-pane archi-active">
    <button type="button" class="btn btn-success mb-3" data-toggle="modal" data-target="#addSettingModal">
        Add New Setting
    </button>
    <form method="POST" action="{{ route('settings.update') }}">
        @csrf
        @foreach ($settings as $setting)
            <div class="mb-3 form-group">
                <label for="{{ $setting->key }}" class="form-label">{{ ucwords(str_replace('_', ' ', $setting->key)) }}</label>
                <input type="text" 
                    class="form-control" 
                    id="{{ $setting->key }}" 
                    name="settings[{{ $setting->key }}]" 
                    value="{{ old('settings.' . $setting->key, $setting->value) }}">
            </div>
        @endforeach
        <button type="submit" class="btn btn-primary">Save Settings</button>
    </form>
</div>

<div class="modal fade" id="addSettingModal" tabindex="-1" role="dialog" aria-labelledby="addSettingModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form method="POST" action="{{ route('settings.store') }}">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSettingModalLabel">Add New Setting</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label for="key">Setting Key</label>
                    <input type="text" name="key" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="value">Setting Value</label>
                    <textarea name="value" class="form-control" rows="3"></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save Setting</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </form>
  </div>
</div>

@endsection

