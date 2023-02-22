import React, { useState } from "react";
import MaterialTable from "material-table";
import { fetchAPIData } from "./Common";

export default function Services(props) {
  const [services, setServices] = useState([]);
  const columns = [
    { title: "ID", field: "id", editable: "false" },
    { title: "SERVICE", field: "SERVICE_NAME" },
    {
      title: "STATUS",
      field: "STATUS",
      lookup: { Y: "Y", N: "N" },
    },
    {
      title: "TIME",
      field: "LAST_UPDATED",
      editable: "false",
    },
  ];
  const rows = props.rows;
  const sendServices = (newData) => {
    fetchAPIData(
      "/dailyreportsapi/services/updateServices.php",
      newData,
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
              const dataUpdate = [...services];
              const index = oldData.tableData.id;
              dataUpdate[index] = newData;
              sendServices(newData);
              setServices([...dataUpdate]);
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
