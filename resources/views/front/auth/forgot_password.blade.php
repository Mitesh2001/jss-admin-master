<div v-if="currentPage == 'forgot-password'">
    <div class="page-title">Membership Portal Forgot Password</div>

    <div id="wrapper-box">
        <div class="p-3 wrapper-child-box">
            <div class="row">
                <div class="col-12 col-md-4 offset-md-4">
                    <div class="text-center">
                        <button type="button" class="btn btn-link link p-0" @click="changePage('login')">
                            Already have an account? Click here to login
                        </button>
                    </div>

                    <form class="form member-form mt-5 px-3" method="post" @submit.prevent="forgotPassword">
                        <ul class="alert alert-danger list-group list-unstyled" v-if="isError" v-cloak>
                            <li class="pl-5" v-for="errorMessage in errorMessages">
                                @{{ errorMessage }}
                            </li>
                        </ul>

                        <div class="form-row">
                            <div class="col-12">
                                <label for="membership-number">Membership Number</label>

                                <div class="input-group mb-2">
                                    <div class="input-group-prepend input-label">
                                        <div class="input-group-text">W02</div>
                                    </div>

                                    <input type="number"
                                        class="form-control number-input"
                                        id="membership-number"
                                        v-model="registerDetails.membership_number"
                                        required
                                    >
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-3">
                            <button type="submit"
                                class="btn btn-primary btn-lg btn-block button-color"
                                :disabled="isDisabledForgotPassword"
                            >
                                Send Email Link
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
