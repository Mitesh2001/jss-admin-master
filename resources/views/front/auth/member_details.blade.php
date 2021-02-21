<div v-if="currentPage == 'member-details'">
    <form class="form member-details-form" method="post" @submit.prevent="updateMemberDetails">
        <div class="page-title">
            <span class="text-muted">
                Membership Details
            </span>
            <span class="text-dark d-none d-lg-inline">
                |
                @{{ memberDetails.first_name }}
                @{{ memberDetails.middle_name }}
                @{{ memberDetails.surname }}
            </span>
        </div>

        <div id="wrapper-box">
            <div class="p-3 wrapper-child-box">
                <ul class="alert alert-danger list-group list-unstyled mb-4" v-if="isError" v-cloak>
                    <li class="pl-5" v-for="errorMessage in errorMessages">
                        @{{ errorMessage }}
                    </li>
                </ul>

                <div class="row">
                    <div class="col-12">
                        <div class="form-row">
                            <div class="col-12 col-md-4">
                                <label for="forename">Forename:</label>

                                <div class="input-group mb-2">
                                    <input type="text"
                                        class="form-control"
                                        id="forename"
                                        v-model="memberDetails.first_name"
                                        readonly
                                    >
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <label for="middle-name">Middle Name:</label>

                                <div class="input-group mb-2">
                                    <input type="text"
                                        class="form-control"
                                        id="middle-name"
                                        v-model="memberDetails.middle_name"
                                        readonly
                                    >
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <label for="surname">Surname:</label>

                                <div class="input-group mb-2">
                                    <input type="text"
                                        class="form-control"
                                        id="surname"
                                        v-model="memberDetails.surname"
                                        readonly
                                    >
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <label for="date-of-birth">Date Of Birth:</label>

                                <div class="input-group mb-3">
                                    <input type="text"
                                        class="form-control"
                                        id="date-of-birth"
                                        v-model="memberDetails.date_of_birth"
                                        readonly
                                    >

                                    <div class="input-group-append">
                                        <span class="input-group-text basic-calender-addon">
                                            <img src="{{ asset('images/calender.png') }}" alt="">
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <label for="gender">Gender:</label>

                                <div class="input-group mb-2">
                                    <input type="text"
                                        class="form-control"
                                        id="gender"
                                        v-model="memberDetails.gender"
                                        readonly
                                    >
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <label for="occupation">Occupation:</label>

                                <div class="input-group mb-2">
                                    <input type="text"
                                        class="form-control"
                                        id="occupation"
                                        v-model="memberDetails.occupation"
                                        required
                                    >
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <label for="email">Email Address:</label>

                                <div class="input-group mb-2">
                                    <input type="text"
                                        class="form-control"
                                        id="email"
                                        v-model="memberDetails.email_address"
                                        required
                                    >
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <label for="phone-number">Phone Number:</label>

                                <div class="input-group mb-2">
                                    <input type="text"
                                        class="form-control"
                                        id="phone-number"
                                        v-model="memberDetails.phone_number"
                                        required
                                    >
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <label for="mobile-number">Mobile Number:</label>

                                <div class="input-group mb-2">
                                    <input type="text"
                                        class="form-control"
                                        id="mobile-number"
                                        v-model="memberDetails.mobile_number"
                                    >
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-12 col-md-6">
                                <label for="address-line-1">Address:</label>

                                <div class="input-group mb-2">
                                    <input type="text"
                                        class="form-control"
                                        id="address-line-1"
                                        v-model="memberDetails.address_line_1"
                                        required
                                    >
                                </div>

                                <div class="input-group mb-2">
                                    <input type="text"
                                        class="form-control"
                                        v-model="memberDetails.address_line_2"
                                    >
                                </div>

                                <label for="ssaa-number">SSAA Number:</label>

                                <div class="input-group mb-2">
                                    <input type="text"
                                        class="form-control"
                                        id="ssaa-number"
                                        v-model="memberDetails.ssaa_number"
                                        readonly
                                    >
                                </div>

                                <label for="ssaa-expiry">SSAA Expiry:</label>

                                <div class="input-group mb-3">
                                    <v-datepicker
                                        class="form-control v-datepicker-input"
                                        id="ssaa-expiry"
                                        :value="memberDetails.ssaa_expiry"
                                        @selected="changeSsaaExpiry"
                                        :bootstrap-styling="true"
                                        :disabled-dates="disabledDates"
                                        required
                                    ></v-datepicker>

                                    <div class="input-group-append">
                                        <span class="input-group-text basic-calender-addon">
                                            <img src="{{ asset('images/calender.png') }}" alt="">
                                        </span>
                                    </div>
                                </div>

                                <label class="mt-1" for="membership-number">Membership Number:</label>

                                <div class="input-group mb-2 mt-1">
                                    <input type="text"
                                        class="form-control"
                                        id="membership-number"
                                        v-model="memberDetails.membership_number"
                                        readonly
                                    >
                                </div>

                                <label for="membership-number">
                                    <strong>Disciplines:</strong>
                                </label>

                                <div class="input-group mb-2">
                                    @{{ memberDetails.disciplines_text }}
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="address-line-1">Suburb:</label>

                                <div class="input-group mb-2" id="suburb-selection">
                                    <v-select class="w-100"
                                        label="text"
                                        :options="suburbs"
                                        v-model="memberDetails.suburb"
                                        :reset-on-options-change="isResetSuburbs"
                                        required
                                    ></v-select>
                                </div>

                                <label for="address-line-1">State:</label>

                                <div class="input-group mb-2">
                                    <select class="form-control"
                                        v-model="memberDetails.state_id"
                                        @change="updateSuburbs(memberDetails.state_id)"
                                        required
                                    >
                                        <option v-for="state in states" :value="state.id">
                                            @{{ state.label }}
                                        </option>
                                    </select>
                                </div>

                                <label for="postcode">Postcode:</label>

                                <div class="input-group mb-2">
                                    <input type="text"
                                        class="form-control"
                                        id="postcode"
                                        v-model="memberDetails.post_code"
                                        required
                                    >
                                </div>

                                <label for="postcode">
                                    <strong>Other Information:</strong>
                                </label>

                                <div class="input-group mb-4">
                                    @{{ memberDetails.other_information }}
                                </div>

                                <label for="membership-expiry">Membership Expiry:</label>

                                <div class="input-group mb-3">
                                    <input type="text"
                                        class="form-control"
                                        id="membership-expiry"
                                        v-model="memberDetails.membership_expiry"
                                        readonly
                                    >

                                    <div class="input-group-append">
                                        <span class="input-group-text basic-calender-addon">
                                            <img src="{{ asset('images/calender.png') }}" alt="">
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button class="btn btn-sm btn-success mt-3 float-right mb-5">Update</button>
    </form>
</div>
