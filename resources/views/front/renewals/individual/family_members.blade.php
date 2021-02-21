<div class="alert alert-warning" role="alert">
    Please review your family, and remove any members if necessary. If you need to add any family members, please have them complete the new member registration form (offline) before completing your renewal.
</div>

<h3>Family Members</h3>

<table class="table table-borderless table-striped-even table-fonts" id="family-member-table">
    <thead class="table-danger">
        <tr>
            <th>
                <i class="fa fa-user"></i>
                Name
            </th>

            <th>
                <i class="fa fa-hashtag"></i>
                Membership Number
            </th>

            <th></th>
        </tr>
    </thead>

    <tbody id="family-member-table-body"></tbody>
</table>

<table id="family-member-template" class="hidden">
    <tbody>
        <tr>
            <td>
                <span class="member-name"></span>
                <span class="text-red no-discipline-message">
                    (Please add disciplines or remove this member.)
                </span>
            </td>

            <td class="membership-number"></td>

            <td>
                <input type="hidden"
                    name="family_member[]"
                    class="family-member-id"
                    value=""
                    data-is-no-discipline=""
                >

                <i class="fa fa-times-circle remove-family-member pointer"></i>
            </td>
        </tr>
    </tbody>
</table>

@push('scripts')
    <script>
        $(document).ready(function () {
            setFamilyMembers();

            $('#family-container').on('click', '#reset-family-members', function() {
                bootbox.confirm({
                    size: "medium",
                    message: "Are you sure you want to reset family members and their disciplines?",
                    callback: function(result) {
                        if (result === true) {
                            $('#family-member-table-body').html(' ');
                            memberIds = copyObject(familyIndividualIds);
                            fIndividualDisciplineIds = Object.assign({}, originalFIndividualDisciplineIds);
                            setFamilyMembers();

                            $('#family-discipline-table tbody').html('');
                            setFamilyDisciplines(fIndividualDisciplineIds);

                            updateRenewalPrice();
                        }
                    }
                });
            });
        });

        function setFamilyMembers() {
            $('#reset-family-members').hide();

            $.each(allFamilyMembers, function(index, familyMember) {
                $('#family-member-table-body').append($('#family-member-template tbody').html());
                var familyMemberRow = $('#family-member-table-body tr').last();
                $(familyMemberRow).addClass('family-member-' + familyMember.id);
                $(familyMemberRow).find('.member-name').html(familyMember.first_name + ' ' + familyMember.surname);

                if (familyMember.disciplines.length) {
                    $(familyMemberRow).find('.no-discipline-message').addClass('hidden');
                }
                $(familyMemberRow).find('.membership-number').html(familyMember.membershipNumber);
                $(familyMemberRow).find('.family-member-id').val(familyMember.id);
                $(familyMemberRow).find('.family-member-id').attr('data-is-no-discipline', familyMember.disciplines.length ? 0 : 1);
            });
        }
    </script>
@endpush
