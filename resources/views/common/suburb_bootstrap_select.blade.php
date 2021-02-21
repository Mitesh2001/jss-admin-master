@push('scripts')
<script>
    var suburbs = @json($suburbs);
    var suburbElement = document.getElementById('suburb');
    var stateElement = document.getElementById('state');
    var getSuburbsUrl = "{{ route('suburbs_for_state') }}";

    $(document).ready(function() {
        $('#suburb').selectpicker({
            liveSearchPlaceholder: 'Select a suburb from the selected state.',
            liveSearch: true,
        });

        setSuburbOptions(suburbs);

        stateElement.addEventListener('change', function(e) {
            $.ajax({
                method: "GET",
                url: getSuburbsUrl,
                data: { 'state_id': this.value }
            }).done(function(suburbs) {
                setSuburbOptions(suburbs);
            });
        });
    });

    function setSuburbOptions(suburbs) {
        var options = '';

        for (var i = 0; i < suburbs.length; i++) {
            var isSelected = suburbs[i].id == selectedSuburbId ? ' selected' : '';
            options += '<option value="' + suburbs[i].id + '"' + isSelected  + '>' + suburbs[i].text + '</option>';
        }

        $('#suburb').html(options);

        $('#suburb').selectpicker('refresh');
    }
</script>
@endpush
