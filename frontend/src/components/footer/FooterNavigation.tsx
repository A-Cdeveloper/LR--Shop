import { Link } from 'react-router';

const menuLinks = [
  { label: 'Home', to: '/' },
  { label: 'Products', to: '/products' },
  { label: 'Cart', to: '/cart' },
  { label: 'Account', to: '/account' },
  { label: 'Contact', to: '/contact' },
  { label: 'Terms of Service', to: '/terms' },
] as const;

const FooterNavigation = () => {
  return (
    <nav aria-label="Footer" className="text-right max-[480px]:text-center">
      <ul className="flex flex-col gap-2 text-[13px]">
        {menuLinks.map((link) => (
          <li key={link.to}>
            <Link
              to={link.to}
              className="text-muted-foreground transition-colors hover:text-foreground"
            >
              {link.label}
            </Link>
          </li>
        ))}
      </ul>
    </nav>
  );
};

export default FooterNavigation;
