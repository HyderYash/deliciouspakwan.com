var API_ROOT_PATH = "";
if (process.env.NODE_ENV === "production") {
  API_ROOT_PATH = "http://www.deliciouspakwan.com";
} else {
  API_ROOT_PATH = "http://local.deliciouspakwan.com";
}
export { API_ROOT_PATH };
