{{-- resources/views/appointments/_form.blade.php --}}
@csrf
@if(isset($method) && strtoupper($method)==='PUT')
  @method('PUT')
@endif
@if(!isset($appointment))
  <input type="hidden" name="contact_id" value="{{ $contact->id }}">
@endif

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
  <div class="field">
    <label>Scheduled For</label>
    <input type="datetime-local" name="scheduled_for"
           value="{{ old('scheduled_for', optional($appointment->scheduled_for ?? null)->format('Y-m-d\TH:i')) }}"
           class="ring-brand">
  </div>
  <div class="field">
    <label>Interested In</label>
    <input name="interested_in" value="{{ old('interested_in', $appointment->interested_in ?? '') }}" class="ring-brand">
  </div>
  <div class="field">
    <label>Result (Confirmation)</label>
    <select name="result_id" class="ring-brand">
      <option value="">Select</option>
      @foreach(($results ?? []) as $r)
        <option value="{{ $r->id }}" @selected(old('result_id',$appointment->result_id ?? '')==$r->id)>
          {{ $r->name }}
        </option>
      @endforeach
    </select>
  </div>
</div>

{{-- Marketing / Sales Users --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
  <div class="field">
    <label>Set By (Marketing)</label>
    <select name="set_by_user_id" class="ring-brand">
      <option value="">Select</option>
      @foreach(($marketingUsers ?? []) as $u)
        <option value="{{ $u->id }}" @selected(old('set_by_user_id',$appointment->set_by_user_id ?? '')==$u->id)>
          {{ $u->name }}
        </option>
      @endforeach
    </select>
  </div>
  <div class="field">
    <label>Sales Rep</label>
    <select name="sales_rep_id" class="ring-brand">
      <option value="">Select</option>
      @foreach(($salesUsers ?? []) as $u)
        <option value="{{ $u->id }}" @selected(old('sales_rep_id',$appointment->sales_rep_id ?? '')==$u->id)>
          {{ $u->name }}
        </option>
      @endforeach
    </select>
  </div>
  <div class="field">
    <label>Product</label>
    <input name="product" value="{{ old('product',$appointment->product ?? '') }}" class="ring-brand">
  </div>
</div>

{{-- Result Ran + Pricing --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
  <div class="field">
    <label>Result Ran</label>
    <select name="result_ran_id" class="ring-brand">
      <option value="">Select</option>
      @foreach(($resultRans ?? []) as $r)
        <option value="{{ $r->id }}" @selected(old('result_ran_id',$appointment->result_ran_id ?? '')==$r->id)>
          {{ $r->name }}
        </option>
      @endforeach
    </select>
  </div>
  <div class="field">
    <label>Price Quoted</label>
    <input type="number" step="0.01" name="price_quoted"
           value="{{ old('price_quoted',$appointment->price_quoted ?? '') }}" class="ring-brand">
  </div>
  <div class="field">
    <label>Price Sold</label>
    <input type="number" step="0.01" name="price_sold"
           value="{{ old('price_sold',$appointment->price_sold ?? '') }}" class="ring-brand">
  </div>
</div>

{{-- Notes / flags --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
  <div class="field md:col-span-2">
    <label>Notes</label>
    <textarea name="notes" rows="4" class="ring-brand">{{ old('notes',$appointment->notes ?? '') }}</textarea>
  </div>

  <div class="flex flex-wrap gap-4">
    <label class="inline-flex items-center gap-2">
      <input type="checkbox" name="result_48hr" value="1" @checked(old('result_48hr',$appointment->result_48hr ?? false))>
      <span>Result 48 hr</span>
    </label>
    <label class="inline-flex items-center gap-2">
      <input type="checkbox" name="result_onspot" value="1" @checked(old('result_onspot',$appointment->result_onspot ?? false))>
      <span>On-Spot</span>
    </label>
    <label class="inline-flex items-center gap-2">
      <input type="checkbox" name="below_par" value="1" @checked(old('below_par',$appointment->below_par ?? false))>
      <span>Below PAR</span>
    </label>
    <label class="inline-flex items-center gap-2">
      <input type="checkbox" name="got_docs" value="1" @checked(old('got_docs',$appointment->got_docs ?? false))>
      <span>Got Docs</span>
    </label>
  </div>
</div>
