import { Paper } from "@material-ui/core";
import React, { useEffect } from "react";
import Header from "../components/common/Header";
import { authenticateAdmin } from "../components/Common";

const AdminHomePage = () => {
  useEffect(() => {
    authenticateAdmin();
  }, []);
  return (
    <div>
      <Header header="Admin Home" />
      <Paper
        elevation={2}
        style={{
          margin: "10px",
          padding: "10px",
          boxShadow: "3px 3px 3px 3px rgba(0, 0, 0, 0.3)",
          marginTop: "20px",
        }}
      >
        <h1>Welcome Admin</h1>
      </Paper>
    </div>
  );
};

export default AdminHomePage;
