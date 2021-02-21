<div v-if="currentPage == 'register'">
    <div class="page-title">Membership Portal Registration</div>

    <div id="wrapper-box">
        <div class="p-3 wrapper-child-box">
            <div class="row">
                <div class="col-12 col-md-4 offset-md-4">
                    <div class="text-center">
                        <button type="button" class="btn btn-link link font-md p-0" @click="changePage('login')">
                            Already have an account? Click here to login
                        </button>
                    </div>

                    <form class="form member-form mt-5" method="post" @submit.prevent="register">
                        <ul class="alert alert-danger list-group list-unstyled" v-if="isError" v-cloak>
                            <li class="pl-5" v-for="errorMessage in errorMessages">
                                @{{ errorMessage }}
                            </li>
                        </ul>

                        <div class="form-row">
                            <div class="col-12 px-5 mb-3">
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

                        <p class="text-center mb-0 login-footer-text">
                            A registration link will be sent to the email address associated with your membership. if you don't receive the email please check your junk folder!
                        </p>

                        <div class="text-center">
                            <button type="button" class="btn btn-link font-italic link" @click="changePage('forgot-password')">
                                <u>
                                    Lost Password?
                                </u>
                            </button>

                            <br><br>

                            <div class="px-5">
                                <button type="submit"
                                    class="btn btn-primary btn-lg btn-block button-color"
                                    :disabled="isDisabledRegister"
                                >
                                    Register Now
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
