import React, { useState } from "react";
import MaterialTable from "material-table";
import { fetchAPIData, getLangName } from "./Common";

export default function ManageLangTable(props) {
  const [lang, setLang] = useState([]);
  const columns = [
    { title: "ID", field: "ID", editable: "false" },
    { title: "Language Name", field: "LANG_NAME" },
  ];
  const rows = props.rows;
  const updateLang = (newData) => {
    const formData = {
      FN_NAME: "updateManageLang",
      techType: sessionStorage.getItem("manageLang"),
      LANG_NAME: newData.LANG_NAME,
      ID: newData.ID,
    };
    fetchAPIData("/", formData, "POST").then((json) => {
      window.location.reload();
      console.log(json);
    });
  };
  return (
    <MaterialTable
      title={getLangName(sessionStorage.getItem("manageLang"))}
      style={{
        margin: "10px",
        padding: "10px",
        boxShadow: "3px 3px 3px 3px rgba(0, 0, 0, 0.3)",
        marginTop: "50px",
      }}
      columns={columns}
      data={rows}
      editable={{
        onRowUpdate: (newData, oldData) =>
          new Promise((resolve, reject) => {
            setTimeout(() => {
              const dataUpdate = [...lang];
              const index = oldData.tableData.id;
              dataUpdate[index] = newData;
              updateLang(newData);
              setLang([...dataUpdate]);
              resolve();
            }, 1000);
          }),
      }}
      options={{
        exportButton: true,
        grouping: true,
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
