<div v-if="currentPage == 'choose-password'">
    <div class="page-title">Membership Portal Choose Password</div>

    <div id="wrapper-box">
        <div class="p-3 wrapper-child-box">
            <div class="row">
                <div class="col-12 col-md-4 offset-md-4">
                    <form class="form member-form px-3" method="post" @submit.prevent="setPasswordToMember">
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

                                    <input type="text"
                                        class="form-control"
                                        id="membership-number"
                                        v-model="registerDetails.membership_number"
                                        readonly
                                    >
                                </div>
                            </div>

                            <div class="col-12 mt-1">
                                <label for="password">Password</label>

                                <div class="input-group mb-2">
                                    <input type="password"
                                        class="form-control"
                                        id="password"
                                        v-model="registerDetails.password"
                                        required
                                    >
                                </div>
                            </div>

                            <div class="col-12 mt-1">
                                <label for="confirm-password">Confirm Password</label>

                                <div class="input-group mb-2">
                                    <input type="password"
                                        class="form-control"
                                        id="confirm-password"
                                        v-model="registerDetails.confirmPassword"
                                        required
                                    >
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg btn-block button-color">
                                Confirm Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
