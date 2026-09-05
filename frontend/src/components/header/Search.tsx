import { Label, Input } from '@shop/ui';
import { SearchIcon } from 'lucide-react';
import { useState } from 'react';

const Search = () => {
  const [search, setSearch] = useState('');

  return (
    <div className="flex w-full max-w-[450px] justify-end px-4 sm:block">
      {/* Mobile (< sm): icon only */}
      <button
        type="button"
        aria-label="Search"
        className="flex size-12 items-center justify-center rounded-full bg-muted text-foreground transition-colors hover:bg-muted/80 sm:hidden "
      >
        <SearchIcon className="size-5" strokeWidth={1.65} />
      </button>

      {/* sm+: full search field */}
      <div className="relative hidden sm:block">
        <Label htmlFor="search" className="sr-only">
          Search
        </Label>
        <SearchIcon
          className="pointer-events-none absolute top-1/2 left-4 size-5 -translate-y-1/2 text-muted-foreground/50"
          strokeWidth={1.65}
          aria-hidden
        />
        <Input
          type="text"
          id="search"
          placeholder="Search"
          className="header-search-input h-12 w-full rounded-3xl border border-primary/10 bg-muted py-6 pr-6 pl-11 shadow-none placeholder:text-muted-foreground/50 focus-visible:ring-0 focus-visible:ring-offset-0"
          value={search}
          onChange={(e) => setSearch(e.target.value)}
        />
      </div>
    </div>
  );
};

export default Search;
