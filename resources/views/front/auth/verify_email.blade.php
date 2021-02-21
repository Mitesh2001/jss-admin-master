<div v-if="currentPage == 'verify-email'">
    <div class="page-title">Membership Portal Registration</div>

    <div id="wrapper-box">
        <div class="p-3 wrapper-child-box">
            <div class="row">
                <div class="col-12 col-md-6 offset-md-3">
                    <h4 class="text-center">
                        @{{ verifyEmailMessage }}
                    </h4>

                    <div class="text-center">
                        <button type="button" class="btn btn-link link font-md p-0" @click="changePage('login')">
                            Click here to login
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
