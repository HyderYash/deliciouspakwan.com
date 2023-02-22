import React from "react";
import MaterialTable from "material-table";

export default function ReportDataTable(props) {
  const columns = props.columns;
  const rows = props.rows;
  return (
    <>
      {props.columns && props.rows !== "" ? (
        <MaterialTable
          title=""
          style={{ width: "100%" }}
          columns={columns}
          data={rows}
          options={{
            exportButton: true,
          }}
        />
      ) : null}
    </>
  );
}
