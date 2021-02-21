<script>
    var token = document.head.querySelector('meta[name="csrf-token"]');

    Vue.use(Vuetable);
    Vue.component('v-select', VueSelect.VueSelect);
    Vue.component('v-datepicker', vuejsDatepicker);

    Vue.component('vuetable-pagination-bootstrap', {
        template: `
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item pointer" :class="{'disabled': isOnFirstPage}">
                        <a href="" class="page-link" @click.prevent="loadPage('prev')">
                        <span>Previous</span>
                        </a>
                    </li>

                    <template v-if="notEnoughPages">
                        <li v-for="n in totalPage" class="page-item pointer" :class="{'active': isCurrentPage(n)}">
                            <a @click.prevent="loadPage(n)" class="page-link" v-html="n"></a>
                        </li>
                    </template>

                    <template v-else>
                        <li v-for="n in windowSize" class="page-item pointer" :class="{'active': isCurrentPage(windowStart+n-1)}">
                            <a @click.prevent="loadPage(windowStart+n-1)" class="page-link" v-html="windowStart+n-1"></a>
                        </li>
                    </template>

                    <li class="page-item pointer" :class="{'disabled': isOnLastPage}">
                        <a href="" class="page-link" @click.prevent="loadPage('next')">
                            <span>Next</span>
                        </a>
                    </li>
                </ul>
            </nav>
        `,
        mixins: [Vuetable.VuetablePaginationMixin]
    });

    var getSuburbsUrl = "{{ route('suburbs_for_state') }}";
    var suburbs = @json($suburbs);
    var states = @json($states);

    var loginMixin = {
        data: function () {
            return {
                loginDetails: {
                    membership_number: '',
                    password: '',
                },
            }
        },

        methods: {
            login: function () {
                var self = this;
                this.removeErrorMessages();

                var requestDetails = {
                    membership_number: this.loginDetails.membership_number,
                    password: this.loginDetails.password,
                    google_recaptcha_token: this.recaptchaToken,
                };

                axios.post('/login', requestDetails)
                .then(function (response) {
                    self.memberDetails = response.data.data.memberDetails;
                    self.attendances = response.data.data.attendances;
                    self.isLoggedIn = true;
                    self.changePage('member-details');

                    self.loginDetails.membership_number = '';
                    self.loginDetails.password = '';
                })
                .catch(function (error) {
                    self.displayErrorMessages(error);
                });
            },
        }

    }

    var registerMixin = {
        data: function () {
            return {
                registerDetails: {
                    membership_number: '',
                    password: '',
                    confirmPassword: '',
                },
                isDisabledRegister: false
            }
        },

        methods: {
            register: function () {
                var self = this;
                this.removeErrorMessages();
                this.isDisabledRegister = true;

                axios.post('/register', {
                    membership_number: this.registerDetails.membership_number,
                    google_recaptcha_token: this.recaptchaToken,
                })
                .then(function (response) {
                    self.verifyEmailMessage = 'We have sent you an email with a registration link and instructions.';
                    self.changePage('verify-email');
                    this.isDisabledRegister = false;
                })
                .catch(function (error) {
                    self.displayErrorMessages(error);
                    this.isDisabledRegister = false;
                });
            },
        }

    }

    var suburbAndStateMixin = {
        data: function () {
            return {
                isResetSuburbs: false,
                states: states,
                suburbs: suburbs,
                isSsaaExpiryUpdated: false
            }
        },

        methods: {
            updateSuburbs: function (stateId) {
                this.isResetSuburbs = true;
                this.removeErrorMessages();
                var self = this;

                axios.get(getSuburbsUrl + '?state_id=' + stateId)
                .then(function (response) {
                    self.suburbs = response.data;
                });
            },

            changeSsaaExpiry: function (selectedDate) {
                this.memberDetails.ssaa_expiry = selectedDate.getFullYear() + '-' + (selectedDate.getMonth() + 1) + '-' +selectedDate.getDate();
                this.isSsaaExpiryUpdated = true;
            },
        }

    }

    var manageMemberMixin = {
        data: function () {
            return {
                memberDetails: {},
                disabledDates: {
                    customPredictor: function (date) {
                        var todayDate = new Date();
                        todayDate.setDate(todayDate.getDate() - 1);

                        if (date < todayDate) {
                            return true;
                        }
                    }
                }
            }
        },

        methods: {
            updateMemberDetails: function () {
                this.removeErrorMessages();

                if (! this.memberDetails.suburb) {
                    alert('Please choose suburb.');
                }


                var self = this;

                axios.post('/update-member-details', {
                    id: this.memberDetails.id,
                    membership_number: this.memberDetails.membership_number,
                    occupation: this.memberDetails.occupation,
                    email_address: this.memberDetails.email_address,
                    phone_number: this.memberDetails.phone_number,
                    mobile_number: this.memberDetails.mobile_number,
                    address_line_1: this.memberDetails.address_line_1,
                    address_line_2: this.memberDetails.address_line_2,
                    suburb_id: this.memberDetails.suburb.id,
                    post_code: this.memberDetails.post_code,
                    state_id: this.memberDetails.state_id,
                    ssaa_expiry: this.isSsaaExpiryUpdated ? this.memberDetails.ssaa_expiry : '',
                    google_recaptcha_token: this.recaptchaToken,
                }).then(function (response) {
                    self.setupGoogleToken();
                    alertify.set('notifier','position', 'top-right');
                    alertify.success(response.data.message, 10);
                }).catch(function (error) {
                    self.displayErrorMessages(error);
                });
            },
        }

    }

    var forgotPasswordMixin = {
        data: function () {
            return {
                isDisabledForgotPassword: false,
            }
        },

        methods: {
            forgotPassword: function () {
                var self = this;
                this.removeErrorMessages();
                this.isDisabledForgotPassword = true;

                axios.post('/forgot-password', {
                    membership_number: this.registerDetails.membership_number,
                    google_recaptcha_token: this.recaptchaToken,
                })
                .then(function (response) {
                    self.verifyEmailMessage = 'We have sent you an email with a link which will allow you to reset your password.';
                    self.changePage('verify-email');
                    self.isDisabledForgotPassword = false;
                })
                .catch(function (error) {
                    self.displayErrorMessages(error);
                    self.isDisabledForgotPassword = false;
                });
            },
        }

    }

    var changePasswordMixin = {
        methods: {
            setPasswordToMember: function () {
                var self = this;
                this.removeErrorMessages();

                axios.post('/change-password', {
                    membership_number: this.registerDetails.membership_number,
                    password: this.registerDetails.password,
                    password_confirmation: this.registerDetails.confirmPassword,
                    google_recaptcha_token: this.recaptchaToken,
                }).then(function (response) {
                    self.isLoggedIn = true;
                    self.memberDetails = response.data.data.memberDetails;
                    self.attendances = response.data.data.attendances;
                    self.changePage('member-details');

                    self.registerDetails.membership_number = '';
                    self.registerDetails.password = '';
                    self.registerDetails.confirmPassword = '';
                }).catch(function (error) {
                    self.displayErrorMessages(error);
                });
            },
        }

    }

    var app = new Vue({
        el: '#application',
        mixins: [loginMixin, registerMixin, forgotPasswordMixin, changePasswordMixin, suburbAndStateMixin, manageMemberMixin],
        data: {
            currentPage: '', // login, register, verify-email, choose-password, forgot-password, reset-password, member-details
            isLoggedIn: false,
            attendances: [],
            isDisabledDownload: false,
            fields: [
                {
                    name: 'event_date',
                    title: 'Date',
                    titleClass: 'bg-dark text-white p-3',
                    dataClass: 'bg-striped',
                },
                {
                    name: 'discipline_label',
                    title: 'Discipline',
                    titleClass: 'bg-dark text-white p-3',
                    dataClass: 'bg-striped',
                },
                {
                    name: 'score',
                    title: 'Score',
                    titleClass: 'bg-dark text-white p-3',
                    dataClass: 'bg-striped',
                },
            ],
            css: {
                table: {
                    tableClass: 'mt-2 mt-sm-5 table table-borderless',
                },
            },
            dataCount: 0,
            isError: false,
            errorMessages: [],
            recaptchaToken: '',
            verifyEmailMessage: '',
        },

        methods: {
            onPaginationData (paginationData) {
                this.$refs.pagination.setPaginationData(paginationData)
            },

            onChangePage (page) {
                this.$refs.vuetable.changePage(page)
            },

            dataManager(sortOrder, pagination) {
                // Code reference => https://codepen.io/ratiw/project/editor/DzmJnA
                var data = this.attendances.data

                // since the filter might affect the total number of records
                // we can ask Vuetable to recalculate the pagination for us
                // by calling makePagination(). this will make VuetablePagination
                // work just like in API mode
                pagination = this.$refs.vuetable.makePagination(data.length)

                // if you don't want to use pagination component, you can just
                // return the data array
                return {
                    pagination: pagination,
                    data: _.slice(data, pagination.from - 1, pagination.to)
                }
            },

            logout: function () {
                var self = this;

                axios.post('/logout')
                .then(function (response) {
                    window.location = '/member-portal';
                });
            },

            displayErrorMessages: function (error) {
                var self = this;
                this.isError = true;
                this.setupGoogleToken();

                var errors = error.response.data.errors;

                if (errors) {
                    for (var error in errors) {
                        this.errorMessages.push(errors[error][0]);
                    }
                    return;
                }

                this.errorMessages.push(error.response.data.message);
            },

            changePage: function (page) {
                this.setupGoogleToken();
                this.currentPage = page;
                this.removeErrorMessages();
            },

            removeErrorMessages: function () {
                this.isError = false;
                this.errorMessages = [];
            },

            printAttandances: function () {
                var self = this;
                this.isDisabledDownload = true;

                axios.post('/print-attendances')
                .then(function (response) {
                    window.open('/download-attendances');
                    self.isDisabledDownload = false;
                });
            },

            setupGoogleToken: function () {
                var self = this;

                grecaptcha.ready(function() {
                    grecaptcha.execute(
                        '{{ config("services.google.recaptcha_site_key") }}',
                        { action: 'member_portal' }
                    ).then(function(token) {
                        self.recaptchaToken = token;
                    });
                });
            },

        },

        created: function() {
            this.setupGoogleToken();
            var memberDetails = @json($memberDetails);
            var currentPage = "{{ $currentPage ?? 'login' }}";

            this.currentPage = currentPage;

            if (currentPage == 'choose-password') {
                this.registerDetails.membership_number = memberDetails.ssaa_number;
                return;
            }

            if (memberDetails.id) {
                this.isLoggedIn = true;
                this.memberDetails = memberDetails;
                this.attendances = @json($attendances);
                this.changePage('member-details');
                return;
            }
        },
    });
</script>
