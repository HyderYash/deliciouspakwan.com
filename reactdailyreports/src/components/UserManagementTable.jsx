import React, { useState } from "react";
import MaterialTable from "material-table";
import { fetchAPIData } from "./Common";

export default function UserManagementTable(props) {
  const [users, setUsers] = useState([]);
  const columns = [
    { title: "ID", field: "ID", editable: "false" },
    { title: "USER NAME", field: "USER_NAME", editable: "false" },
    { title: "USER EMAIL", field: "USER_EMAIL", editable: "false" },
    { title: "USER LAST LOGGED", field: "USER_LAST_LOGGED", editable: "false" },
    {
      title: "USER STATUS",
      field: "USER_STATUS",
      lookup: { Y: "Y", N: "N" },
    },
  ];
  const rows = props.rows;
  const sendUsers = (newData) => {
    const formData = {
      ID: newData.ID,
      USER_STATUS: newData.USER_STATUS,
    };
    fetchAPIData(
      "/dailyreportsapi/login/updateUserStatus.php",
      formData,
      "POST"
    ).then((json) => {
      window.location.reload();
      console.log(json);
    });
  };
  return (
    <MaterialTable
      title=""
      style={{ margin: "10px", marginTop: "100px" }}
      columns={columns}
      data={rows}
      editable={{
        onRowUpdate: (newData, oldData) =>
          new Promise((resolve, reject) => {
            setTimeout(() => {
              const dataUpdate = [...users];
              const index = oldData.tableData.id;
              dataUpdate[index] = newData;
              sendUsers(newData);
              setUsers([...dataUpdate]);
              resolve();
            }, 1000);
          }),
      }}
      options={{
        exportButton: true,
        grouping: true,
        selection: true,
        actionsColumnIndex: -1,
      }}
      // actions={[
      //   {
      //     tooltip: "Remove All Selected Users",
      //     icon: "delete",
      //     onClick: (evt, data) =>
      //       alert("You want to delete " + data.length + " rows"),
      //   },
      // ]}
    />
  );
}
