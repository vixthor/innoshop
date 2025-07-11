@props(['item'])

<div class="col-md-3 col-sm-6 mb-3">
  <label for="{{ $item['name'] }}" class="form-label form-label-sm">{{ $item['label'] }}</label>
  <input type="text"
         name="{{ $item['name'] }}"
         id="autocomplete-{{ $item['name'] }}"
         class="form-control form-control-sm"
         value="{{ request($item['name']) }}"
         placeholder="{{ $item['label'] }}">
  <input type="hidden"
         name="{{ $item['hidden_input_name'] ?? ($item['name'].'_id') }}"
         id="autocomplete-{{ $item['name'] }}-id"
         value="{{ request($item['hidden_input_name'] ?? ($item['name'].'_id')) }}">
</div>

@push('footer')
<script>
$(function () {
  const $input = $('#autocomplete-{{ $item['name'] }}');
  const $hiddenInput = $('#autocomplete-{{ $item['name'] }}-id');
  
  $input.on('keyup', function() {
    if ($(this).val() === '') {
      $hiddenInput.val('');
    }
  });
  
  $input.autocomplete({
    'source': function (request, response) {
      axios.get('{{ $item['url'] }}', { params: { keyword: request } })
        .then(function (res) {
          response($.map(res.data, function (item) {
            return {
              label: item['{{ $item['option_label_key'] ?? 'name' }}'],
              value: item['{{ $item['input_value_key'] ?? ($item['option_label_key'] ?? 'name') }}'],
              hidden: item['{{ $item['hidden_value_key'] ?? 'id' }}']
            };
          }));
        });
    },
    'select': function (item) {
      $input.val(item.value);
      $hiddenInput.val(item.hidden);
      return false;
    }
  });
});
</script>
@endpush 