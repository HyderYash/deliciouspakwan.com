const Header = () => {
  return (
    <nav className="bg-white dark:bg-gray-800 fixed top-0 z-50 w-full">
      <div className="px-4 mx-auto max-w-7xl">
        <div className="flex items-center justify-between h-16">
          <a className="flex-shrink-0" href="/">
            <img
              className="w-8 h-8"
              src="/assets/images/dp_large.png"
              alt="Workflow"
            />
          </a>
          <div className="flex items-baseline ml-10 space-x-4">
            <a
              className="text-gray-300  hover:text-gray-800 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium"
              href="http://deliciouspakwan.com"
            >
              Watch Delicious Pakwan Videos
            </a>
          </div>
        </div>
      </div>
    </nav>
  );
};
export default Header;
