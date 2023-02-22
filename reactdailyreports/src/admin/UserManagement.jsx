import React, { useEffect, useState } from "react";
import { Button } from "@material-ui/core";
import Header from "../components/Header";
import UserManagementTable from "../components/UserManagementTable";
import { Link } from "react-router-dom";
import { fetchAPIData } from "../components/Common";
import Loader from "../components/Loader";
import PersonIcon from "@material-ui/icons/Person";

const AdminHome = () => {
  const [users, setUsers] = useState([]);
  const [loading, setLoading] = useState(true);
  useEffect(() => {
    fetchAPIData("/dailyreportsapi/login/getUsers.php").then((json) => {
      if (json.status === "Success") {
        setUsers(json.records);
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
      <Link to="/admin">
        <Button
          variant="contained"
          color="primary"
          style={{ margin: "10px", float: "right" }}
          endIcon={<PersonIcon />}
        >
          Services
        </Button>
      </Link>
      <UserManagementTable rows={users} />
    </div>
  );
};

export default AdminHome;
