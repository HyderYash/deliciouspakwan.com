import React, { useEffect, useState } from "react";
import { Button } from "@material-ui/core";
import Header from "../components/Header";
import Services from "../components/Services";
import AddCircleOutlineIcon from "@material-ui/icons/AddCircleOutline";
import { Link } from "react-router-dom";
import { fetchAPIData } from "../components/Common";
import Loader from "../components/Loader";

const AdminHome = () => {
  const [services, setServices] = useState([]);
  const [loading, setLoading] = useState(true);
  useEffect(() => {
    fetchAPIData("/dailyreportsapi/services/getServices.php").then((json) => {
      if (json.status === "Success") {
        setServices(json.records);
        setLoading(false);
      } else {
        console.log(json);
        setLoading(false);
      }
    });
  }, []);
  return (
    <div>
      {loading ? <Loader /> : null}
      <Header header="Admin Home" />
      <Link to="/addservice">
        <Button
          variant="contained"
          color="secondary"
          style={{ margin: "10px", float: "right" }}
          endIcon={<AddCircleOutlineIcon />}
        >
          Add New Service
        </Button>
      </Link>
      <Services rows={services} />
    </div>
  );
};

export default AdminHome;
