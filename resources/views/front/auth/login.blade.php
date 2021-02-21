<div v-if="currentPage == 'login'">
    <div class="page-title">Membership Portal Login</div>

    <div id="wrapper-box">
        <div class="p-3 wrapper-child-box">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-4 offset-md-4">
                    <div class="text-center">
                        <button type="button" class="btn btn-link link font-md p-0" @click="changePage('register')">
                            <u>First time user? Click here to register</u>
                        </button>
                    </div>

                    <form class="form mt-5 member-form px-3" method="post" @submit.prevent="login">
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
                                        v-model="loginDetails.membership_number"
                                        required
                                    >
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <label for="password">Password</label>

                                <button type="button" class="btn btn-link p-0 float-right font-italic link" @click="changePage('forgot-password')">
                                    <u>
                                        Lost Password?
                                    </u>
                                </button>

                                <div class="input-group">
                                    <input type="password"
                                        class="form-control"
                                        id="password"
                                        v-model="loginDetails.password"
                                        required
                                    >
                                </div>
                            </div>
                        </div>

                        <div class="text-center my-3 mt-5">
                            <button type="submit" class="btn btn-primary btn-lg btn-block button-color">
                                Login
                            </button>
                        </div>
                    </form>

                    <p class="text-center login-footer-text">
                        Your membership number is your SSAA number, as shown on your JSS membership card.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
