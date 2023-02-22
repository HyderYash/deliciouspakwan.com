import Link from "next/link";
import Image from "next/image";

const Header = () => {
  return (
    <nav className="bg-white dark:bg-gray-800 fixed top-0 z-50 w-full">
      <div className="px-4 mx-auto max-w-7xl">
        <div className="flex items-center justify-between h-16">
          <Link className="flex-shrink-0" href="/">
            <Image
              className="w-8 h-8"
              src="/assets/images/dp_large.png"
              alt="Workflow"
            />
          </Link>
          <div className="flex items-baseline ml-10 space-x-4">
            <Link
              className="text-gray-300  hover:text-gray-800 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium"
              href="http://deliciouspakwan.com"
            >
              Watch Delicious Pakwan Videos
            </Link>
          </div>
        </div>
      </div>
    </nav>
  );
};
export default Header;
