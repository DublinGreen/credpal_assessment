import axios from "axios";
import store from "../store";

export const manageDocumentsCrudMixin = {
    data() {
        return {
            showLoadingIcon: false,
            showSuccessAlert: false,
            showErrorAlert: false,
            search: "",
            dataRecords: null,
            recordsToDisplay: [],
            singleRecord: null,
            dialog: false,
            userData: null,
            headers: [{
                    text: "Document Type",
                    align: "start",
                    sortable: true,
                    value: "document_type"
                },
                {
                    text: "Status",
                    sortable: true,
                    value: "status"
                },
                {
                    text: "Description",
                    sortable: true,
                    value: "description"
                },
                {
                    text: "Expiration Date",
                    sortable: true,
                    value: "expiring_date"
                },
                {
                    text: "Expiration Interval (Months)",
                    sortable: false,
                    value: "expiration_interval"
                },
                // {
                //     text: "Last Updated",
                //     sortable: true,
                //     value: "updated_at"
                // },
                {
                    text: "Actions",
                    value: "actions",
                    sortable: false
                }
            ],
            editedIndex: -1,
            editedItem: {
                name: "",
                score: 0
            }
        }
    },
    computed: {
        formTitle() {
            return "Edit Document";
        }
    },
    watch: {
        dialog(val) {
            val || this.close();
        }
    },
    methods: {
        forceFileDownload(documentObj) {
            let url = store.state.urlStore.serverUrl + documentObj.path;
            console.log("url >>> ", url);


            if (url !== store.state.urlStore.baseUrl) {
                // const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement("a");
                // console.log(link);
                link.href = url;
                link.target = "_blank";
                link.setAttribute('download', documentObj.name) //or any other extension
                document.body.appendChild(link);
                link.click();
            } else {
                this.dialogCallResponse = true;
                this.showLoadingIcon = false;
                this.showErrorIcon = true;
                this.sendingRequest = false;
            }
        },
        downloadWithAxios(documentObj) {
            let vmObjectInstance = this;
            console.log("documentObj >>> ", documentObj);
            axios({
                    method: "get",
                    url: store.state.urlStore.serverUrl + documentObj.path,
                    responseType: "arraybuffer"
                })
                .then(response => {
                    console.log("response >>>", response);
                    vmObjectInstance.showSuccessIcon = false;
                    vmObjectInstance.forceFileDownload(response);
                })
                .catch(function(error) {
                    console.error(error);
                    vmObjectInstance.dialogCallResponse = true;
                    vmObjectInstance.showLoadingIcon = false;
                    vmObjectInstance.showErrorIcon = true;
                    vmObjectInstance.sendingRequest = false;
                });
        },
        friendlyData(databaseDateformat) {
            let dateParts = databaseDateformat.split("-");
            let jsDate = new Date(dateParts[0], dateParts[1] - 1, dateParts[2].substr(0, 2));
            return jsDate;
        },
        editItem(item) {
            this.editedIndex = this.recordsToDisplay.indexOf(item);
            this.editedItem = Object.assign({}, item);
            this.dialog = true;
        },
        close() {
            this.dialog = false;
            setTimeout(() => {
                this.editedItem = Object.assign({}, this.defaultItem);
                this.editedIndex = -1;
            }, 300);
        },
        save() {
            let vmObjectInstance = this;
            this.showLoadingIcon = true;
            // console.log(this.editedItem);
            let dataToSend = {
                id: this.editedItem.id,
                status: this.editedItem.status,
                description: this.editedItem.description
            };

            let config = {
                headers: {
                    Authorization: `Bearer ${localStorage.getItem(
                  store.state.setTokenLocalStorageKey
                )}`
                }
            };

            axios
                .post(this.endpointUpdate, dataToSend, config)
                .then(function(response) {
                    console.log(response.data);
                    if (response.data.status) {
                        vmObjectInstance.showLoadingIcon = false;
                        vmObjectInstance.showSuccessAlert = true;

                        setTimeout(() => {
                            vmObjectInstance.showSuccessAlert = false;
                        }, store.state.alertTimeout);
                    }

                })
                .catch(function(error) {
                    console.error(error);
                    vmObjectInstance.showLoadingIcon = false;
                    vmObjectInstance.showErrorAlert = true;

                    setTimeout(() => {
                        vmObjectInstance.showErrorAlert = false;
                    }, store.state.alertTimeout);
                });

            if (this.editedIndex > -1) {
                Object.assign(this.recordsToDisplay[this.editedIndex], this.editedItem);
            } else {
                this.recordsToDisplay.push(this.editedItem);
            }
            this.close();
        }
    }
}