import React from "react";

const index = ({
  displayPageNumbers,
  currentPageNum,
  setCurrentPageNum,
  totalNumPages,
}) => {
  return (
    <nav className="mt-5 mb-2 flex justify-center items-center">
      <ul className="inline-flex -space-x-px">
        <li>
          <span
            onClick={() => {
              if (currentPageNum > 1) {
                sessionStorage.setItem("currPageNum", currentPageNum - 1);
                setCurrentPageNum(currentPageNum - 1);
              }
            }}
            className="px-3 py-2 ml-0 leading-tight border hover:text-white bg-gray-900 border-gray-700 text-gray-400 cursor-pointer font-bold"
          >
            Prev
          </span>
        </li>
        {displayPageNumbers.map((x) => {
          return (
            <li key={x}>
              <span
                onClick={() => {
                  sessionStorage.setItem("currPageNum", x);
                  setCurrentPageNum(x);
                }}
                className={`px-3 py-2 leading-tight border border-gray-700  cursor-pointer font-bold ${
                  currentPageNum === x
                    ? "bg-gray-300 text-gray-900 text-lg"
                    : "bg-gray-900 text-gray-400"
                }`}
              >
                {x}
              </span>
            </li>
          );
        })}

        <li>
          <span
            onClick={() => {
              if (currentPageNum < totalNumPages) {
                sessionStorage.setItem("currPageNum", currentPageNum + 1);
                setCurrentPageNum(currentPageNum + 1);
              }
            }}
            className="px-3 py-2 ml-0 leading-tight border hover:text-white bg-gray-900 border-gray-700 text-gray-400 cursor-pointer font-bold"
          >
            Next
          </span>
        </li>
      </ul>
    </nav>
  );
};

export default index;
