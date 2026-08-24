import Logo from './Logo';
import Search from './Search';
import UserArea from './UserArea';

const Header = () => {
  return (
    <header className="py-10 flex justify-between">
      <div className="gap-4 min-w-[100px] md:min-w-[180px]">
        <Logo />
      </div>
      <div className="flex flex-1 items-center justify-between gap-0">
        <Search />
        <UserArea />
      </div>
    </header>
  );
};

export default Header;
