<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App{
/**
 * App\BranchCode
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $label
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @method static \Illuminate\Database\Eloquent\Builder|BranchCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BranchCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BranchCode query()
 * @method static \Illuminate\Database\Eloquent\Builder|BranchCode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BranchCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BranchCode whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BranchCode whereUpdatedAt($value)
 */
	class BranchCode extends \Eloquent {}
}

namespace App{
/**
 * App\CalendarEvent
 *
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activitiesCount
 * @property-read \App\Discipline $discipline
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\CalendarEventScore[] $scores
 * @property-read int|null $scoresCount
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEvent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEvent newQuery()
 * @method static \Illuminate\Database\Query\Builder|CalendarEvent onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEvent query()
 * @method static \Illuminate\Database\Query\Builder|CalendarEvent withTrashed()
 * @method static \Illuminate\Database\Query\Builder|CalendarEvent withoutTrashed()
 */
	class CalendarEvent extends \Eloquent {}
}

namespace App{
/**
 * App\CalendarEventScore
 *
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activitiesCount
 * @property-read \App\Individual $individual
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEventScore newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEventScore newQuery()
 * @method static \Illuminate\Database\Query\Builder|CalendarEventScore onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEventScore query()
 * @method static \Illuminate\Database\Query\Builder|CalendarEventScore withTrashed()
 * @method static \Illuminate\Database\Query\Builder|CalendarEventScore withoutTrashed()
 */
	class CalendarEventScore extends \Eloquent {}
}

namespace App{
/**
 * App\Discipline
 *
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activitiesCount
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $captains
 * @property-read int|null $captainsCount
 * @method static \Illuminate\Database\Eloquent\Builder|Discipline newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Discipline newQuery()
 * @method static \Illuminate\Database\Query\Builder|Discipline onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Discipline query()
 * @method static \Illuminate\Database\Query\Builder|Discipline withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Discipline withoutTrashed()
 */
	class Discipline extends \Eloquent {}
}

namespace App{
/**
 * App\EmailType
 *
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|EmailType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailType query()
 */
	class EmailType extends \Eloquent {}
}

namespace App{
/**
 * App\Event
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int|null $individualId
 * @property int|null $typeId
 * @property string|null $comments
 * @property string|null $happenedAt
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activitiesCount
 * @property-read bool $formattedHappenedAt
 * @property-read \App\EventType|null $type
 * @method static \Illuminate\Database\Eloquent\Builder|Event newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Event newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Event query()
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereHappenedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereIndividualId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereUpdatedAt($value)
 */
	class Event extends \Eloquent {}
}

namespace App{
/**
 * App\EventType
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $label
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @method static \Illuminate\Database\Eloquent\Builder|EventType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EventType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EventType query()
 * @method static \Illuminate\Database\Eloquent\Builder|EventType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventType whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventType whereUpdatedAt($value)
 */
	class EventType extends \Eloquent {}
}

namespace App{
/**
 * App\Family
 *
 * @mixin \Eloquent
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activitiesCount
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Individual[] $individuals
 * @property-read int|null $individualsCount
 * @method static \Illuminate\Database\Eloquent\Builder|Family newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Family newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Family query()
 * @method static \Illuminate\Database\Eloquent\Builder|Family whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Family whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Family whereUpdatedAt($value)
 */
	class Family extends \Eloquent {}
}

namespace App{
/**
 * App\Firearm
 *
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activitiesCount
 * @property-read \App\Discipline $discipline
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Individual[] $individuals
 * @property-read int|null $individualsCount
 * @property-read \App\FirearmType $type
 * @method static \Illuminate\Database\Eloquent\Builder|Firearm newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Firearm newQuery()
 * @method static \Illuminate\Database\Query\Builder|Firearm onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Firearm query()
 * @method static \Illuminate\Database\Query\Builder|Firearm withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Firearm withoutTrashed()
 */
	class Firearm extends \Eloquent {}
}

namespace App{
/**
 * App\FirearmType
 *
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activitiesCount
 * @method static \Illuminate\Database\Eloquent\Builder|FirearmType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FirearmType newQuery()
 * @method static \Illuminate\Database\Query\Builder|FirearmType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|FirearmType query()
 * @method static \Illuminate\Database\Query\Builder|FirearmType withTrashed()
 * @method static \Illuminate\Database\Query\Builder|FirearmType withoutTrashed()
 */
	class FirearmType extends \Eloquent {}
}

namespace App{
/**
 * App\Gender
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $label
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @method static \Illuminate\Database\Eloquent\Builder|Gender newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Gender newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Gender query()
 * @method static \Illuminate\Database\Eloquent\Builder|Gender whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gender whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gender whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gender whereUpdatedAt($value)
 */
	class Gender extends \Eloquent {}
}

namespace App{
/**
 * App\IdCard
 *
 * @mixin \Eloquent
 * @property-read \App\Individual $individual
 * @method static \Illuminate\Database\Eloquent\Builder|IdCard newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IdCard newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IdCard query()
 */
	class IdCard extends \Eloquent {}
}

namespace App{
/**
 * App\Individual
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string|null $surname
 * @property string|null $middleName
 * @property string|null $firstName
 * @property string|null $dateOfBirth
 * @property int|null $genderId
 * @property string|null $emailAddress
 * @property string|null $phoneNumber
 * @property string|null $mobileNumber
 * @property string|null $occupation
 * @property string|null $addressLine1
 * @property string|null $addressLine2
 * @property int|null $suburbId
 * @property int|null $stateId
 * @property string|null $postCode
 * @property int $pensionCard
 * @property int $isCommitteeMember
 * @property int $isClubLifetimeMember
 * @property int|null $branchCodeId
 * @property string|null $wwcCardNumber
 * @property string|null $wwcExpiryDate
 * @property int|null $familyId
 * @property string|null $password
 * @property string|null $rememberToken
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property \Illuminate\Support\Carbon|null $deletedAt
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activitiesCount
 * @property-read \App\BranchCode|null $branchCode
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Discipline[] $disciplines
 * @property-read int|null $disciplinesCount
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Event[] $events
 * @property-read int|null $eventsCount
 * @property-read \App\Gender|null $gender
 * @property-read \App\IdCard|null $idCard
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\IdCard[] $idCards
 * @property-read int|null $idCardsCount
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Key[] $issuedKeys
 * @property-read int|null $issuedKeysCount
 * @property-read \App\IndividualMembership|null $membership
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\RangeOfficer[] $officers
 * @property-read int|null $officersCount
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Receipt[] $receipts
 * @property-read int|null $receiptsCount
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\IndividualRenewal[] $renewalEntries
 * @property-read int|null $renewalEntriesCount
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Renewal[] $renewals
 * @property-read int|null $renewalsCount
 * @property-read \App\IndividualSsaa|null $ssaa
 * @property-read \App\State|null $state
 * @property-read \App\Suburb|null $suburb
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Firearm[] $supportedFirearms
 * @property-read int|null $supportedFirearmsCount
 * @method static \Illuminate\Database\Eloquent\Builder|Individual newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Individual newQuery()
 * @method static \Illuminate\Database\Query\Builder|Individual onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Individual query()
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereAddressLine1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereAddressLine2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereBranchCodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereEmailAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereFamilyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereGenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereIsClubLifetimeMember($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereIsCommitteeMember($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereMiddleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereMobileNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereOccupation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual wherePensionCard($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual wherePostCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereSuburbId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereSurname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereWwcCardNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Individual whereWwcExpiryDate($value)
 * @method static \Illuminate\Database\Query\Builder|Individual withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Individual withoutTrashed()
 */
	class Individual extends \Eloquent {}
}

namespace App{
/**
 * App\IndividualMembership
 *
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activitiesCount
 * @property-read \App\Individual $individual
 * @property-read \App\MembershipType $type
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualMembership newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualMembership newQuery()
 * @method static \Illuminate\Database\Query\Builder|IndividualMembership onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualMembership query()
 * @method static \Illuminate\Database\Query\Builder|IndividualMembership withTrashed()
 * @method static \Illuminate\Database\Query\Builder|IndividualMembership withoutTrashed()
 */
	class IndividualMembership extends \Eloquent {}
}

namespace App{
/**
 * App\IndividualRenewal
 *
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activitiesCount
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Discipline[] $disciplines
 * @property-read int|null $disciplinesCount
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Individual[] $familyMembers
 * @property-read int|null $familyMembersCount
 * @property-read \App\Individual $individual
 * @property-read IndividualRenewal $parentRenewal
 * @property-read \App\Renewal|null $renewal
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualRenewal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualRenewal newQuery()
 * @method static \Illuminate\Database\Query\Builder|IndividualRenewal onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualRenewal query()
 * @method static \Illuminate\Database\Query\Builder|IndividualRenewal withTrashed()
 * @method static \Illuminate\Database\Query\Builder|IndividualRenewal withoutTrashed()
 */
	class IndividualRenewal extends \Eloquent {}
}

namespace App{
/**
 * App\IndividualSsaa
 *
 * @mixin \Eloquent
 * @property-read \App\Individual $individual
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualSsaa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualSsaa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualSsaa query()
 */
	class IndividualSsaa extends \Eloquent {}
}

namespace App{
/**
 * App\Key
 *
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activitiesCount
 * @property-read \App\Individual $individual
 * @method static \Illuminate\Database\Eloquent\Builder|Key newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Key newQuery()
 * @method static \Illuminate\Database\Query\Builder|Key onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Key query()
 * @method static \Illuminate\Database\Query\Builder|Key withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Key withoutTrashed()
 */
	class Key extends \Eloquent {}
}

namespace App{
/**
 * App\MembershipType
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $label
 * @property string|null $price
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipType query()
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipType whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipType wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipType whereUpdatedAt($value)
 */
	class MembershipType extends \Eloquent {}
}

namespace App{
/**
 * App\PaymentType
 *
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentType query()
 */
	class PaymentType extends \Eloquent {}
}

namespace App{
/**
 * App\PrintRunIdCard
 *
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|PrintRunIdCard newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PrintRunIdCard newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PrintRunIdCard query()
 */
	class PrintRunIdCard extends \Eloquent {}
}

namespace App{
/**
 * App\RangeOfficer
 *
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activitiesCount
 * @property-read \App\Discipline $discipline
 * @property-read \App\Individual $individual
 * @method static \Illuminate\Database\Eloquent\Builder|RangeOfficer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RangeOfficer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RangeOfficer query()
 */
	class RangeOfficer extends \Eloquent {}
}

namespace App{
/**
 * App\Receipt
 *
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activitiesCount
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Individual[] $individuals
 * @property-read int|null $individualsCount
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ReceiptItem[] $items
 * @property-read int|null $itemsCount
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ReceiptPayment[] $payments
 * @property-read int|null $paymentsCount
 * @method static \Illuminate\Database\Eloquent\Builder|Receipt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Receipt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Receipt query()
 */
	class Receipt extends \Eloquent {}
}

namespace App{
/**
 * App\ReceiptItem
 *
 * @mixin \Eloquent
 * @property-read \App\ReceiptItemCode $code
 * @property-read \App\Discipline $discipline
 * @property-read \App\Receipt $receipt
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptItem query()
 */
	class ReceiptItem extends \Eloquent {}
}

namespace App{
/**
 * App\ReceiptItemCode
 *
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activitiesCount
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptItemCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptItemCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptItemCode query()
 */
	class ReceiptItemCode extends \Eloquent {}
}

namespace App{
/**
 * App\ReceiptPayment
 *
 * @mixin \Eloquent
 * @property-read string $formattedPaidAt
 * @property-read \App\PaymentType $type
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptPayment query()
 */
	class ReceiptPayment extends \Eloquent {}
}

namespace App{
/**
 * App\Renewal
 *
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activitiesCount
 * @property-read \App\IndividualRenewal $iRenewal
 * @property-read \App\Individual $individual
 * @property-read \App\Receipt $receipt
 * @property-read \App\RenewalRun $renewalRun
 * @method static \Illuminate\Database\Eloquent\Builder|Renewal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Renewal newQuery()
 * @method static \Illuminate\Database\Query\Builder|Renewal onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Renewal query()
 * @method static \Illuminate\Database\Query\Builder|Renewal withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Renewal withoutTrashed()
 */
	class Renewal extends \Eloquent {}
}

namespace App{
/**
 * App\RenewalRun
 *
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\RenewalRunEmail[] $emails
 * @property-read int|null $emailsCount
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\RenewalRunEntity[] $entities
 * @property-read int|null $entitiesCount
 * @method static \Illuminate\Database\Eloquent\Builder|RenewalRun newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RenewalRun newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RenewalRun query()
 */
	class RenewalRun extends \Eloquent {}
}

namespace App{
/**
 * App\RenewalRunEmail
 *
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|RenewalRunEmail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RenewalRunEmail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RenewalRunEmail query()
 */
	class RenewalRunEmail extends \Eloquent {}
}

namespace App{
/**
 * App\RenewalRunEntity
 *
 * @mixin \Eloquent
 * @property-read \App\Individual $individual
 * @method static \Illuminate\Database\Eloquent\Builder|RenewalRunEntity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RenewalRunEntity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RenewalRunEntity query()
 */
	class RenewalRunEntity extends \Eloquent {}
}

namespace App{
/**
 * App\SparkpostTemplate
 *
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|SparkpostTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SparkpostTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SparkpostTemplate query()
 */
	class SparkpostTemplate extends \Eloquent {}
}

namespace App{
/**
 * App\SparkpostTransmission
 *
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|SparkpostTransmission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SparkpostTransmission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SparkpostTransmission query()
 */
	class SparkpostTransmission extends \Eloquent {}
}

namespace App{
/**
 * App\State
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $label
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @method static \Illuminate\Database\Eloquent\Builder|State newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|State newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|State query()
 * @method static \Illuminate\Database\Eloquent\Builder|State whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|State whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|State whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|State whereUpdatedAt($value)
 */
	class State extends \Eloquent {}
}

namespace App{
/**
 * App\StaticType
 *
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|StaticType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StaticType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StaticType query()
 */
	class StaticType extends \Eloquent {}
}

namespace App{
/**
 * App\Suburb
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $label
 * @property int $stateId
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @method static \Illuminate\Database\Eloquent\Builder|Suburb newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Suburb newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Suburb query()
 * @method static \Illuminate\Database\Eloquent\Builder|Suburb whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Suburb whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Suburb whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Suburb whereStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Suburb whereUpdatedAt($value)
 */
	class Suburb extends \Eloquent {}
}

namespace App{
/**
 * App\User
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int|null $individualId
 * @property string $username
 * @property string $password
 * @property int $type 1: Admin 2: Captain
 * @property string|null $google2faSecret
 * @property string|null $rememberToken
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property \Illuminate\Support\Carbon|null $deletedAt
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activitiesCount
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Discipline[] $disciplines
 * @property-read int|null $disciplinesCount
 * @property-read \App\Individual|null $individual
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notificationsCount
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Query\Builder|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGoogle2faSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIndividualId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 * @method static \Illuminate\Database\Query\Builder|User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|User withoutTrashed()
 */
	class User extends \Eloquent {}
}

