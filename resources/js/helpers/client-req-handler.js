import axios from "axios";
import { Notify, Dialog } from "quasar";

export function default_submit_handler(form, url) {
  form.clearErrors();
  form.post(url,
    {
      preserveScroll: true,
      onError: (response) => {
        Notify.create({
          message: response.message,
          icon: "info",
          color: "negative",
          actions: [
            { icon: "close", color: "white", round: true, dense: true },
          ],
        });
      },
    }
  );
}

export function default_delete_handler(confirmationMessage, url, fetchItems, loading) {
  Dialog.create({
    title: "Confirm",
    icon: "question",
    message: confirmationMessage,
    focus: "cancel",
    cancel: true,
    persistent: true,
  }).onOk(() => {
    loading.value = true;
    axios
      .post(url)
      .then((response) => {
        Notify.create(response.data.message);
        fetchItems();
      })
      .finally(() => {
        loading.value = false;
      })
      .catch((error) => {
        let message = "";
        if (error.response.data && error.response.data.message) {
          message = error.response.data.message;
        } else if (error.message) {
          message = error.message;
        }
        Notify.create({ message: message, color: "red" });
        console.log(error);
      });
  });
}

export function default_fetch_handler(pagination, filter, props, rows, url, loading) {
  let params = {
    page: pagination.value.page,
    per_page: pagination.value.rowsPerPage,
    order_by: pagination.value.sortBy,
    order_type: pagination.value.descending ? "desc" : "asc",
    filter: filter.value,
  };

  if (props != null) {
    params.page = props.pagination.page;
    params.per_page = props.pagination.rowsPerPage;
    params.order_by = props.pagination.sortBy;
    params.order_type = props.pagination.descending ? "desc" : "asc";
    params.filter = props.filter;
    filter.value = props.filter;
  }

  loading.value = true;

  axios
    .get(url, { params: params })
    .then((response) => {
      rows.value = response.data.data;
      pagination.value.page = response.data.current_page;
      pagination.value.rowsPerPage = response.data.per_page;
      pagination.value.rowsNumber = response.data.total;
      if (props) {
        pagination.value.sortBy = props.pagination.sortBy;
        pagination.value.descending = props.pagination.descending;
      }
    })
    .finally(() => {
      loading.value = false;
    });
}
