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
 * @property int $id
 * @property string $eventDate
 * @property int $scoreType 1:Point based, 2:Deviation based
 * @property int $disciplineId
 * @property int $isFinalised
 * @property int $isAttendanceTracked
 * @property int $isPublic
 * @property string|null $startTime
 * @property string|null $title
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property \Illuminate\Support\Carbon|null $deletedAt
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activitiesCount
 * @property-read \App\Discipline $discipline
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\CalendarEventScore[] $scores
 * @property-read int|null $scoresCount
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEvent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEvent newQuery()
 * @method static \Illuminate\Database\Query\Builder|CalendarEvent onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEvent query()
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEvent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEvent whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEvent whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEvent whereDisciplineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEvent whereEventDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEvent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEvent whereIsAttendanceTracked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEvent whereIsFinalised($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEvent whereIsPublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEvent whereScoreType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEvent whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEvent whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEvent whereUpdatedAt($value)
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
 * @property int $id
 * @property int $calendarEventId
 * @property int $individualId
 * @property string $score
 * @property int|null $scoreUnit 1:mm, 2:inch
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property \Illuminate\Support\Carbon|null $deletedAt
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activitiesCount
 * @property-read \App\Individual $individual
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEventScore newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEventScore newQuery()
 * @method static \Illuminate\Database\Query\Builder|CalendarEventScore onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEventScore query()
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEventScore whereCalendarEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEventScore whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEventScore whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEventScore whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEventScore whereIndividualId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEventScore whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEventScore whereScoreUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalendarEventScore whereUpdatedAt($value)
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
 * @property int $id
 * @property string|null $label
 * @property string|null $adultPrice
 * @property string|null $familyPrice
 * @property string|null $pensionerPrice
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property \Illuminate\Support\Carbon|null $deletedAt
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activitiesCount
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $captains
 * @property-read int|null $captainsCount
 * @method static \Illuminate\Database\Eloquent\Builder|Discipline newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Discipline newQuery()
 * @method static \Illuminate\Database\Query\Builder|Discipline onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Discipline query()
 * @method static \Illuminate\Database\Eloquent\Builder|Discipline whereAdultPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discipline whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discipline whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discipline whereFamilyPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discipline whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discipline whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discipline wherePensionerPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discipline whereUpdatedAt($value)
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
 * @property int $id
 * @property string $label
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @method static \Illuminate\Database\Eloquent\Builder|EmailType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailType query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailType whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailType whereUpdatedAt($value)
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
 * @property int $id
 * @property int $firearmTypeId
 * @property string $make
 * @property string $model
 * @property string $calibre
 * @property string $serial
 * @property int $disciplineId
 * @property string $supportGrantedAt
 * @property string|null $supportRemovedAt
 * @property string|null $markAsDisposedAt
 * @property string|null $supportReason
 * @property string|null $disposedReason
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property \Illuminate\Support\Carbon|null $deletedAt
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
 * @method static \Illuminate\Database\Eloquent\Builder|Firearm whereCalibre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Firearm whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Firearm whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Firearm whereDisciplineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Firearm whereDisposedReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Firearm whereFirearmTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Firearm whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Firearm whereMake($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Firearm whereMarkAsDisposedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Firearm whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Firearm whereSerial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Firearm whereSupportGrantedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Firearm whereSupportReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Firearm whereSupportRemovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Firearm whereUpdatedAt($value)
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
 * @property int $id
 * @property string $label
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property \Illuminate\Support\Carbon|null $deletedAt
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activitiesCount
 * @method static \Illuminate\Database\Eloquent\Builder|FirearmType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FirearmType newQuery()
 * @method static \Illuminate\Database\Query\Builder|FirearmType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|FirearmType query()
 * @method static \Illuminate\Database\Eloquent\Builder|FirearmType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FirearmType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FirearmType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FirearmType whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FirearmType whereUpdatedAt($value)
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
 * @property int $id
 * @property int $individualId
 * @property int $isAddedForPrintrun
 * @property string|null $printedAt
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read \App\Individual $individual
 * @method static \Illuminate\Database\Eloquent\Builder|IdCard newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IdCard newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IdCard query()
 * @method static \Illuminate\Database\Eloquent\Builder|IdCard whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IdCard whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IdCard whereIndividualId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IdCard whereIsAddedForPrintrun($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IdCard wherePrintedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IdCard whereUpdatedAt($value)
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
 * @property int $id
 * @property int $individualId
 * @property string|null $membershipNumber
 * @property string|null $joinDate
 * @property int $status
 * @property int|null $typeId
 * @property string|null $notes
 * @property string|null $expiry
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property \Illuminate\Support\Carbon|null $deletedAt
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activitiesCount
 * @property-read \App\Individual $individual
 * @property-read \App\MembershipType|null $type
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualMembership newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualMembership newQuery()
 * @method static \Illuminate\Database\Query\Builder|IndividualMembership onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualMembership query()
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualMembership whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualMembership whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualMembership whereExpiry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualMembership whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualMembership whereIndividualId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualMembership whereJoinDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualMembership whereMembershipNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualMembership whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualMembership whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualMembership whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualMembership whereUpdatedAt($value)
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
 * @property int $id
 * @property int|null $individualId
 * @property int|null $parentRenewalId The individual_renewal_id of the 1st family member renewal submission.
 * @property string|null $firstName
 * @property string|null $middleName
 * @property string|null $surname
 * @property string|null $emailAddress
 * @property string|null $dateOfBirth
 * @property int|null $genderId
 * @property string|null $mobileNumber
 * @property string|null $phoneNumber
 * @property string|null $addressLine1
 * @property string|null $addressLine2
 * @property int|null $suburbId
 * @property int|null $stateId
 * @property int|null $postCode
 * @property string|null $membershipNo
 * @property int|null $typeId
 * @property string|null $membershipPrice
 * @property string|null $ssaaExpiry
 * @property string|null $amount
 * @property string|null $discount
 * @property int|null $paymentType 0: 2nd or 3rd family member | 1: Offline | 2:Online
 * @property string|null $transactionNo
 * @property string|null $receivedAmount
 * @property string|null $renewalApplierFullName
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property \Illuminate\Support\Carbon|null $deletedAt
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activitiesCount
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Discipline[] $disciplines
 * @property-read int|null $disciplinesCount
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Individual[] $familyMembers
 * @property-read int|null $familyMembersCount
 * @property-read \App\Individual|null $individual
 * @property-read IndividualRenewal|null $parentRenewal
 * @property-read \App\Renewal|null $renewal
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualRenewal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualRenewal newQuery()
 * @method static \Illuminate\Database\Query\Builder|IndividualRenewal onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualRenewal query()
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualRenewal whereAddressLine1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualRenewal whereAddressLine2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualRenewal whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualRenewal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualRenewal whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualRenewal whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualRenewal whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualRenewal whereEmailAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualRenewal whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualRenewal whereGenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualRenewal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualRenewal whereIndividualId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualRenewal whereMembershipNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualRenewal whereMembershipPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualRenewal whereMiddleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualRenewal whereMobileNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualRenewal whereParentRenewalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualRenewal wherePaymentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualRenewal wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualRenewal wherePostCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualRenewal whereReceivedAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualRenewal whereRenewalApplierFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualRenewal whereSsaaExpiry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualRenewal whereStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualRenewal whereSuburbId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualRenewal whereSurname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualRenewal whereTransactionNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualRenewal whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualRenewal whereUpdatedAt($value)
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
 * @property int $id
 * @property int $individualId
 * @property int|null $ssaaNumber
 * @property int $ssaaStatus
 * @property string|null $ssaaExpiry
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read \App\Individual $individual
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualSsaa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualSsaa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualSsaa query()
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualSsaa whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualSsaa whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualSsaa whereIndividualId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualSsaa whereSsaaExpiry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualSsaa whereSsaaNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualSsaa whereSsaaStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IndividualSsaa whereUpdatedAt($value)
 */
	class IndividualSsaa extends \Eloquent {}
}

namespace App{
/**
 * App\Key
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $individualId
 * @property int $keyType 1:General 2:Committee
 * @property int $keyNumber
 * @property string $issuedAt
 * @property string $depositAmount
 * @property string|null $returnedAt
 * @property string|null $loosedAt
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property \Illuminate\Support\Carbon|null $deletedAt
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activitiesCount
 * @property-read \App\Individual $individual
 * @method static \Illuminate\Database\Eloquent\Builder|Key newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Key newQuery()
 * @method static \Illuminate\Database\Query\Builder|Key onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Key query()
 * @method static \Illuminate\Database\Eloquent\Builder|Key whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Key whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Key whereDepositAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Key whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Key whereIndividualId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Key whereIssuedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Key whereKeyNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Key whereKeyType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Key whereLoosedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Key whereReturnedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Key whereUpdatedAt($value)
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
 * @property int $id
 * @property string $label
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentType query()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentType whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentType whereUpdatedAt($value)
 */
	class PaymentType extends \Eloquent {}
}

namespace App{
/**
 * App\PrintRunIdCard
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $cardId
 * @property string|null $fullName
 * @property string|null $membershipNumber
 * @property string|null $memberSince
 * @property string|null $disciplineList
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @method static \Illuminate\Database\Eloquent\Builder|PrintRunIdCard newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PrintRunIdCard newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PrintRunIdCard query()
 * @method static \Illuminate\Database\Eloquent\Builder|PrintRunIdCard whereCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintRunIdCard whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintRunIdCard whereDisciplineList($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintRunIdCard whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintRunIdCard whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintRunIdCard whereMemberSince($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintRunIdCard whereMembershipNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintRunIdCard whereUpdatedAt($value)
 */
	class PrintRunIdCard extends \Eloquent {}
}

namespace App{
/**
 * App\RangeOfficer
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $individualId
 * @property int $disciplineId
 * @property string $addedDate
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activitiesCount
 * @property-read \App\Discipline $discipline
 * @property-read \App\Individual $individual
 * @method static \Illuminate\Database\Eloquent\Builder|RangeOfficer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RangeOfficer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RangeOfficer query()
 * @method static \Illuminate\Database\Eloquent\Builder|RangeOfficer whereAddedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RangeOfficer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RangeOfficer whereDisciplineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RangeOfficer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RangeOfficer whereIndividualId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RangeOfficer whereUpdatedAt($value)
 */
	class RangeOfficer extends \Eloquent {}
}

namespace App{
/**
 * App\Receipt
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $datedAt
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
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
 * @method static \Illuminate\Database\Eloquent\Builder|Receipt whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receipt whereDatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receipt whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receipt whereUpdatedAt($value)
 */
	class Receipt extends \Eloquent {}
}

namespace App{
/**
 * App\ReceiptItem
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $receiptId
 * @property int|null $disciplineId NULL:For membership,n:For Discipline
 * @property int|null $receiptItemCodeId
 * @property string|null $description
 * @property string $amount
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read \App\ReceiptItemCode|null $code
 * @property-read \App\Discipline|null $discipline
 * @property-read \App\Receipt $receipt
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptItem whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptItem whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptItem whereDisciplineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptItem whereReceiptId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptItem whereReceiptItemCodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptItem whereUpdatedAt($value)
 */
	class ReceiptItem extends \Eloquent {}
}

namespace App{
/**
 * App\ReceiptItemCode
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string|null $label
 * @property string|null $description
 * @property string $amount
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activitiesCount
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptItemCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptItemCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptItemCode query()
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptItemCode whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptItemCode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptItemCode whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptItemCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptItemCode whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptItemCode whereUpdatedAt($value)
 */
	class ReceiptItemCode extends \Eloquent {}
}

namespace App{
/**
 * App\ReceiptPayment
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $receiptId
 * @property int|null $typeId
 * @property string $amount
 * @property string|null $notes
 * @property string|null $stripeTransferNo
 * @property string|null $transactionFee
 * @property string $paidAt
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read string $formattedPaidAt
 * @property-read \App\PaymentType|null $type
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptPayment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptPayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptPayment whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptPayment wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptPayment whereReceiptId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptPayment whereStripeTransferNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptPayment whereTransactionFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptPayment whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReceiptPayment whereUpdatedAt($value)
 */
	class ReceiptPayment extends \Eloquent {}
}

namespace App{
/**
 * App\Renewal
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int|null $individualId
 * @property int|null $individualRenewalId
 * @property int|null $renewalRunId
 * @property int $approved
 * @property int $pending
 * @property int|null $receiptId
 * @property int $confirmationEmailQueued
 * @property int $confirmationEmailed
 * @property int $cardPrintStatus 1:Not exported,2:Exported,3:Printed
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property \Illuminate\Support\Carbon|null $deletedAt
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activitiesCount
 * @property-read \App\IndividualRenewal|null $iRenewal
 * @property-read \App\Individual|null $individual
 * @property-read \App\Receipt|null $receipt
 * @property-read \App\RenewalRun|null $renewalRun
 * @method static \Illuminate\Database\Eloquent\Builder|Renewal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Renewal newQuery()
 * @method static \Illuminate\Database\Query\Builder|Renewal onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Renewal query()
 * @method static \Illuminate\Database\Eloquent\Builder|Renewal whereApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Renewal whereCardPrintStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Renewal whereConfirmationEmailQueued($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Renewal whereConfirmationEmailed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Renewal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Renewal whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Renewal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Renewal whereIndividualId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Renewal whereIndividualRenewalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Renewal whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Renewal wherePending($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Renewal whereReceiptId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Renewal whereRenewalRunId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Renewal whereUpdatedAt($value)
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
 * @property int $id
 * @property string|null $period
 * @property string|null $paymentDueDate
 * @property string|null $startDate
 * @property string|null $expiryDate
 * @property int $status 0: Inactive 1: Active
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\RenewalRunEmail[] $emails
 * @property-read int|null $emailsCount
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\RenewalRunEntity[] $entities
 * @property-read int|null $entitiesCount
 * @method static \Illuminate\Database\Eloquent\Builder|RenewalRun newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RenewalRun newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RenewalRun query()
 * @method static \Illuminate\Database\Eloquent\Builder|RenewalRun whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RenewalRun whereExpiryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RenewalRun whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RenewalRun wherePaymentDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RenewalRun wherePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RenewalRun whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RenewalRun whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RenewalRun whereUpdatedAt($value)
 */
	class RenewalRun extends \Eloquent {}
}

namespace App{
/**
 * App\RenewalRunEmail
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $renewalRunId
 * @property int $sparkpostTemplateId
 * @property int|null $individualId
 * @property string $sentAt
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @method static \Illuminate\Database\Eloquent\Builder|RenewalRunEmail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RenewalRunEmail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RenewalRunEmail query()
 * @method static \Illuminate\Database\Eloquent\Builder|RenewalRunEmail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RenewalRunEmail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RenewalRunEmail whereIndividualId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RenewalRunEmail whereRenewalRunId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RenewalRunEmail whereSentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RenewalRunEmail whereSparkpostTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RenewalRunEmail whereUpdatedAt($value)
 */
	class RenewalRunEmail extends \Eloquent {}
}

namespace App{
/**
 * App\RenewalRunEntity
 *
 * @mixin \Eloquent
 * @property int $renewalRunId
 * @property int $individualId
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read \App\Individual $individual
 * @method static \Illuminate\Database\Eloquent\Builder|RenewalRunEntity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RenewalRunEntity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RenewalRunEntity query()
 * @method static \Illuminate\Database\Eloquent\Builder|RenewalRunEntity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RenewalRunEntity whereIndividualId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RenewalRunEntity whereRenewalRunId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RenewalRunEntity whereUpdatedAt($value)
 */
	class RenewalRunEntity extends \Eloquent {}
}

namespace App{
/**
 * App\SparkpostTemplate
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $templateId
 * @property int|null $emailTypeId
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @method static \Illuminate\Database\Eloquent\Builder|SparkpostTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SparkpostTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SparkpostTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder|SparkpostTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SparkpostTemplate whereEmailTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SparkpostTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SparkpostTemplate whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SparkpostTemplate whereTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SparkpostTemplate whereUpdatedAt($value)
 */
	class SparkpostTemplate extends \Eloquent {}
}

namespace App{
/**
 * App\SparkpostTransmission
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $rejectedRecipients
 * @property int $acceptedRecipients
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @method static \Illuminate\Database\Eloquent\Builder|SparkpostTransmission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SparkpostTransmission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SparkpostTransmission query()
 * @method static \Illuminate\Database\Eloquent\Builder|SparkpostTransmission whereAcceptedRecipients($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SparkpostTransmission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SparkpostTransmission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SparkpostTransmission whereRejectedRecipients($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SparkpostTransmission whereUpdatedAt($value)
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

