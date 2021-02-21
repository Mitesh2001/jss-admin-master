<div v-if="currentPage == 'attendance'" id="attendances-container">
    <div class="page-title">
        <span class="text-muted">
            ATTENDANCE
        </span>

        <span class="text-dark d-none d-lg-inline">
            |
            @{{ memberDetails.first_name }}
            @{{ memberDetails.surname }}
        </span>
    </div>

    <div id="wrapper-box">
        <div class="py-3 wrapper-child-box px-2 px-sm-5 table-responsive">
            <div class="mx-sm-5">
                <div class="responsive-attendances">
                    <button class="btn btn-outline-success float-right"
                        @click="printAttandances"
                        :disabled="isDisabledDownload"
                    >
                        Download
                    </button>

                    <vuetable ref="vuetable"
                        :api-mode="false"
                        :fields="fields"
                        :data="attendances"
                        :data-total="dataCount"
                        :data-manager="dataManager"
                        data-path="data"
                        pagination-path="pagination"
                        :per-page="15"
                        :css="css.table"
                        @vuetable:pagination-data="onPaginationData"
                        no-data-template="No records found!"

                    ></vuetable>
                </div>

                <vuetable-pagination-bootstrap ref="pagination"
                    class="float-right"
                    @vuetable-pagination:change-page="onChangePage"
                ></vuetable-pagination-bootstrap>
            </div>
        </div>
    </div>
</div>
