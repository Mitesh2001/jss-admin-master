@extends('front.renewals.layout', [
    'stripe' => true
])

@section('content')
<form method="post"
    class="renewal-form"
    id="individual-renewal-form"
>
    @csrf

    <input type="hidden" name="stripe_token">

    <div class="wizard-navigation">
        <ul>
            <li>
                <a href="#individual-details" data-toggle="tab">
                    Individual Details
                </a>
            </li>

            <li>
                <a href="#membership-details" data-toggle="tab">
                    Membership Details
                </a>
            </li>

            <li>
                <a href="#other-details" data-toggle="tab">
                    Other Details
                </a>
            </li>

            <li>
                <a href="#confirmation" data-toggle="tab">
                    Confirmation
                </a>
            </li>
        </ul>
    </div>

    <div class="tab-content" id="individual">
        @include('front.renewals.individual.individual_details')

        @include('front.renewals.individual.membership_details')

        @include('front.renewals.individual.other_details')

        @include('front.renewals.individual.confirmation')
    </div>

    <div class="wizard-footer">
        <div class="pull-right">
            <input type='button' class='btn btn-next btn-fill btn-danger btn-wd' name='next' value='Next'>
            <input type='submit' class='btn btn-finish btn-fill btn-danger btn-wd' name='finish' value='Make payment'>
        </div>

        <div class="pull-left">
            <input type='button' class='btn btn-previous btn-fill btn-default btn-wd' name='previous' value='Previous'>
        </div>

        <div class="clearfix"></div>
    </div>
</form>

@endsection

@push('scripts')
<script>
    var discount = 0;
    var disciplinesDetails = @json($disciplines) || [];
    var allFamilyMembers = @json(optional($family)->individuals) || [];

    var isFamilyWithoutDisciplines = {{ $isFamilyWithoutDisciplines }};
    var isIndividualWithoutDisciplines = {{ $isIndividualWithoutDisciplines }};
    var currentIndividual = @json($individual);
    var submitButton = $('.btn-finish')[0];
    var familyIndividualIds = @json($familyIndividualIds) || [];
    var memberIds = copyObject(familyIndividualIds);

    var individualDisciplines = @json(optional($individual)->disciplines) || [];
    var familyIndividuals = @json(optional($family)->individuals) || [];
    var membershipTypes = @json($membershipTypes);

    // If the individual is the committee member or Family member have already paid the renewal.
    // then payment is not required
    if ($('input[name=is_family_renewal_already_paid]').val()) {
        $('.btn-finish').val('Submit');
    };

    $(document).ready(function() {
        updateRenewalPrice();

        $(document).on("keypress", ":input:not(textarea):not([type=submit])", function(event) {
            if (event.keyCode == 13) {
                event.preventDefault();
            }
        });
    });

    function isNumber(evt, element, isPostCode) {
        evt = (evt) ? evt : window.event;

        if (window.getSelection().toString()) {
            element.value = "";
        }

        var charCode = (evt.which) ? evt.which : evt.keyCode;

        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }

        if (isPostCode && evt.target.value.length == 4) {
            return false;
        }

        return true;
    }

    function updateRenewalPrice() {
        $('#discount-container').hide();
        $('#discount-details-modal #discount-details-table-body').html(' ');

        var totalPrice = parseInt($("input[name='type_id']:checked").attr('data-price'));
        var typeId = $("input[name='type_id']:checked").val();
        var disciplinePriceType = getDisciplinePriceType();
        var disciplinesTableName = getDisciplinesTableName(typeId);
        var discount = 0;
        var doesFamilyContainOneSpecialMember = doesFamilyContainOnlyOneSpecialMember();

        if (
            typeId == 2 && doesFamilyContainOneSpecialMember
        ) {
            var individual = getIndividualDetails(doesFamilyContainOneSpecialMember);
            var membershipTypeId = 1;

            if (individual.pension_card) {
                membershipTypeId = 3;
            }

            var membershipType = membershipTypes.filter(function(membershipType) {
                return membershipType.id === membershipTypeId;
            })[0];
            discount = parseInt(membershipType.price);

            var discountableIndividual = getDiscountableMembersText(isFamily = true);

            setDiscountDetailsRow('Family Membership' + discountableIndividual, discount);
        }

        if (
            ((currentIndividual.is_committee_member || currentIndividual.is_club_lifetime_member) &&
            $.inArray(typeId, ['1', '3']) != -1) ||
            (typeId == 2 && isMoreThanOneSpecialMember())
        ) {
            discount = totalPrice;

            var membershipDiscountLabel = getMembershipLabel(typeId);

            var discountableIndividual = getDiscountableMembersText(isFamily = typeId == 2);

            setDiscountDetailsRow(membershipDiscountLabel + discountableIndividual, discount);
        }

        var disciplineRows = $('#' + disciplinesTableName + ' tbody .discipline-row');

        $.each(disciplineRows, function(index, disciplineRow) {
            var price = getDiscilinePrice(disciplineRow, typeId, disciplinePriceType);

            if (typeId != 2) {
                $(disciplineRow).find('.discipline-price').html(price);
                discount += getDiscilineDiscount(disciplineRow, price);
            } else {
                discount += getFamilyDiscilineDiscount(disciplineRow, price);
            }

            totalPrice += parseInt(price);
        });

        if (discount) {
            $('#discount-container').show();
            $('#discount-container #total-discount').html(discount.toFixed(2));
            $('#renewal-total-discount').val(discount.toFixed(2));
            totalPrice -= discount
        } else {
            $('#renewal-total-discount').val(0);
        }
        totalPrice = totalPrice ? totalPrice : 0;
        $('#renewal-price').html(totalPrice.toFixed(2));
        $('#total-amount').html(totalPrice.toFixed(2));
        $('#renewal-total-amount').val(totalPrice.toFixed(2));

        updateRenewalTotalTable();

        return totalPrice;
    }

    function getDisciplineDetails(disciplineId) {
        var disciplineDetails = {};

        $.each(disciplinesDetails, function(index, discipline) {
            if (discipline.id == disciplineId) {
                disciplineDetails = discipline;
                return false;
            }
        });

        return disciplineDetails;
    }

    function updateRenewalTotalTable() {
        if ($('input[name=is_family_renewal_already_paid]').val()) {
            return;
        }

        var selectedMembership = $("input[name='type_id']:checked");
        var membershipPrice = $(selectedMembership).attr('data-price');
        var typeId = $(selectedMembership).val();
        var membershipLabel = getMembershipLabel(typeId);
        var disciplinesTableName = getDisciplinesTableName(typeId);

        $('#renewal-total-table tbody').html($('#' + disciplinesTableName + ' tbody').html());
        var totalRows = $('#renewal-total-table tbody tr');
        $.each(totalRows, function(index, totalRow) {
            $(totalRow).find('td').last().remove();
        });

        $('#renewal-total-table tbody').prepend('<tr><td>' + membershipLabel + '</td><td>$' + membershipPrice + '</td></tr>');
    }

    function getFamilyDisciplinePrice(discipline, individualId, isPensioner) {
        if (isPensioner) {
            return discipline.pensioner_price;
        }

        if (parseInt(individualId) == 0) {
            return discipline.family_price;
        }

        return discipline.adult_price;
    }

    function updatesAsPerType(newTypeValue) {
        $('#family-container').addClass('hidden');
        $('#family-discipline-table').addClass('hidden');
        $('#discipline-table').removeClass('hidden');
        $('#reset-family-disciplines').hide();
        $('#reset-disciplines').hide();
        $('.family-membership-message').hide();

        if (! $('.btn-finish').length) {
            $('.wizard-footer .pull-right').append(submitButton);
        }

        if (newTypeValue == 2) {
            $('#family-container').removeClass('hidden');
            $('#family-discipline-table').removeClass('hidden');
            $('#discipline-table').addClass('hidden');
            $('.family-membership-message').show();

            if (isFamilyWithoutDisciplines) {
                $('.btn-finish').remove();
            }
        } else {
            if (isIndividualWithoutDisciplines) {
                $('.btn-finish').remove();
            }
        }

        updateRenewalPrice();
    }

    function getDisciplinePriceType() {
        var typeId = $("input[name='type_id']:checked").val();

        return typeId == 1 ? 'adult_price' : (typeId == 2 ? 'family_price' : 'pensioner_price');
    }

    function getDisciplinesTableName(typeId) {
        return typeId == 2 ? 'family-discipline-table' : 'discipline-table';
    }

    function getDiscilinePrice(disciplineRow, typeId, disciplinePriceType) {
        var disciplineId = $(disciplineRow).attr('data-id');
        var discipline = getDisciplineDetails(parseInt(disciplineId));

        if (typeId != 2) {
            return discipline[disciplinePriceType];
        }

        return getFamilyDisciplinePrice(
            discipline,
            parseInt($(disciplineRow).attr('data-individual-id')),
            parseInt($(disciplineRow).attr('data-is-pensioner'))
        );
    }

    function getMembershipLabel(typeId) {
        return typeId == 1 ? 'Adult Membership' : (typeId == 2 ? 'Family Membership' : 'Pensioner Membership');
    }

    function getDiscountableMembersText(isFamily) {
        if (! isFamily) {
            return getDiscountableMemberTextFor(
                currentIndividual.is_committee_member,
                currentIndividual.is_club_lifetime_member,
                currentIndividual.first_name,
                currentIndividual.first_name
            );
        }

        var committeeMemberNames = [];
        var clubLifetimeMemberNames = [];

        $.each(memberIds, function(index, memberId) {
            var individual = getIndividualDetails(memberId);

            if (individual.is_committee_member) {
                committeeMemberNames.push([individual.first_name]);
            }

            if (individual.is_club_lifetime_member) {
                clubLifetimeMemberNames.push([individual.first_name]);
            }
        });

        return getDiscountableMemberTextFor(
            committeeMemberNames.length,
            clubLifetimeMemberNames.length,
            committeeMemberNames.join(', '),
            clubLifetimeMemberNames.join(', ')
        );
    }

    function getDiscountableDisciplineText(individualIds, disciplineId) {
        var lifetimeMemberNames = [];

        $.each(individualIds, function(index, memberId) {
            var individual = getIndividualDetails(memberId);
            $.each(individual.disciplines, function(index, discipline) {
                if (disciplineId == discipline.id && discipline.pivot.is_lifetime_member) {
                    lifetimeMemberNames.push([individual.first_name]);
                }
            });
        });

        return getDiscountableMemberTextFor(
            isCommitteeMember = 0,
            isClubLifetimeMember = 1,
            committeeMemberNames = '',
            lifetimeMemberNames.join(', ')
        );
    }

    function getDiscountableMemberTextFor(
        isCommitteeMember, isClubLifetimeMember,
        committeeMemberNames, clubLifetimeMemberNames
    ) {
        var discountText = '';

        if (isCommitteeMember) {
            discountText = discountText + ' - (Committee member: ' + committeeMemberNames + ')';
        }

        if (isClubLifetimeMember) {
            discountText = discountText + ' - (Lifetime member: ' + clubLifetimeMemberNames + ')';
        }

        return discountText;
    }

    function getIndividualDetails(individualId) {
        var individualDetails = [];

        $.each(individuals, function(index, individual) {
            if (individual.id == individualId) {
                individualDetails = individual;
                return false;
            }
        });

        return individualDetails;
    }

    function doesFamilyContainOnlyOneSpecialMember() {
        var freeMembershipMemberCount = getFreeMembershipMemberCount();
        if (freeMembershipMemberCount[0] == 1) { // Only one special member
            return freeMembershipMemberCount[1][0];
        }

        return false;
    }

    function isMoreThanOneSpecialMember() {
        if (getFreeMembershipMemberCount()[0] >= 2) {
            return true;
        }

        return false;
    }

    function getFreeMembershipMemberCount() {
        var totalFreeMembershipMember = 0;
        var totalPaidMembershipMemberIds = [];

        $.each(memberIds, function(index, memberId) {
            var memberDetails = getIndividualDetails(memberId);

            if (
                memberDetails.is_committee_member ||
                memberDetails.is_club_lifetime_member
            ) {
                totalFreeMembershipMember++;

                totalPaidMembershipMemberIds.push([memberId]);
            }
        });

        return [totalFreeMembershipMember, totalPaidMembershipMemberIds];
    }

    function getDiscilineDiscount(disciplineRow, price) {
        var disciplineId = $(disciplineRow).attr('data-id');

        var individualDiscipline = individualDisciplines.filter(function(discipline) {
            return discipline.pivot.discipline_id === parseInt(disciplineId);
        })[0];

        if (individualDiscipline.pivot.is_lifetime_member) {
            var discountableIndividual = getDiscountableMemberTextFor(
                isCommitteeMember = 0,
                isClubLifetimeMember = 1,
                committeeMemberNames = '',
                currentIndividual.first_name
            );;

            setDiscountDetailsRow(individualDiscipline.label + discountableIndividual, price);

            return parseInt(price);
        }

        return 0;
    }

    function getFamilyDiscilineDiscount(disciplineRow, price) {
        var disciplineId = $(disciplineRow).attr('data-id');
        var individualIds = $(disciplineRow).attr('data-individual-ids').split(",");
        var individualId = $(disciplineRow).attr('data-individual-id');
        var isPensioner = $(disciplineRow).attr('data-is-pensioner');

        // Returns the family member name as per discipline is lifetime member
        var familyDisciplineLabel = getDiscountableDisciplineText(individualIds, disciplineId);

        if (individualIds.length > 1) {
            var discipline = getDisciplineDetails(disciplineId);
            var disciplineGroupByDiscount = getDisciplinesGroupByDiscount(discipline, individualIds, isPensioner);

            if (disciplineGroupByDiscount['discountable'].length == memberIds.length) {
                setDiscountDetailsRow(
                    discipline.label + familyDisciplineLabel,
                    discipline.family_price
                );

                return parseInt(discipline.family_price);
            }

            if (disciplineGroupByDiscount['discountable'].length == memberIds.length - 1) {
                var familyIndividualDiscipline = disciplineGroupByDiscount['notDiscountable'][0];
                if (familyIndividualDiscipline.individual.pension_card) {
                    setDiscountDetailsRow(
                        discipline.label + familyDisciplineLabel,
                        parseInt(discipline.family_price) - discipline.pensioner_price
                    );

                    return parseInt(discipline.family_price) - discipline.pensioner_price;
                }

                setDiscountDetailsRow(
                    discipline.label + familyDisciplineLabel,
                    parseInt(discipline.family_price) - discipline.adult_price
                );
                return parseInt(discipline.family_price) - discipline.adult_price;
            }

            return 0;
        }

        var discount = 0;
        $.each(familyIndividuals, function(index, familyIndividual) {
            if (familyIndividual.id != parseInt(individualId)) {
                return;
            }
            $.each(familyIndividual.disciplines, function(index, discipline) {
                if (discipline.pivot.is_lifetime_member == 1 &&
                    discipline.pivot.discipline_id === parseInt(disciplineId)
                ) {
                    if (familyIndividual.pension_card == 1) {
                        setDiscountDetailsRow(
                            discipline.label + familyDisciplineLabel,
                            discipline.pensioner_price
                        );

                        discount += discipline.pensioner_price;
                        return;
                    }

                    setDiscountDetailsRow(
                        discipline.label + familyDisciplineLabel,
                        discipline.adult_price
                    );
                    discount += discipline.adult_price;
                }
            });
        });

        return parseInt(discount);
    }

    function getDisciplinesGroupByDiscount(discipline, individualIds) {
        var familyMembers = {
            'discountable': new Array(),
            'notDiscountable': new Array()
        };

        $.each(individualIds, function(index, individualId) {
            $.each(familyIndividuals, function(index, familyIndividual) {
                $.each(familyIndividual.disciplines, function(index, familydiscipline) {
                    if (
                        familydiscipline.pivot.individual_id == individualId &&
                        familydiscipline.pivot.discipline_id == discipline.id
                    ) {
                        familydiscipline.individual = familyIndividual;

                        if (familydiscipline.pivot.is_lifetime_member) {
                            familyMembers['discountable'].push(familydiscipline);
                        } else {
                            familyMembers['notDiscountable'].push(familydiscipline);
                        }
                    }
                });
            });
        });

        return familyMembers;
    }

    function setDiscountDetailsRow(label, price) {
        $('#discount-details-modal #discount-details-table-body').append('<tr><td>' + label + '</td><td>$' + parseInt(price).toFixed(2) + '</td></tr>');
    }
</script>
@endpush
