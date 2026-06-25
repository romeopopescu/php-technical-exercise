import { describe, it, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import UserTable from '../UserTable.vue';

describe('UserTable', () => {
  const users = [
    {
      id: 1,
      hrId: 'E1001',
      firstName: 'Alice',
      lastName: 'Adams',
      email: 'alice@example.com',
      department: 'Engineering',
      isActive: true,
    },
    {
      id: 2,
      hrId: 'E1002',
      firstName: 'Bob',
      lastName: 'Brown',
      email: 'bob@example.com',
      department: 'Sales',
      isActive: false,
    },
  ];

  it('renders a row per user with their details', () => {
    const wrapper = mount(UserTable, { props: { users } });

    const rows = wrapper.findAll('tbody tr');
    expect(rows).toHaveLength(2);
    expect(rows[0].text()).toContain('E1001');
    expect(rows[0].text()).toContain('Alice');
    expect(rows[0].text()).toContain('Yes');
    expect(rows[1].text()).toContain('E1002');
    expect(rows[1].text()).toContain('Bob');
    expect(rows[1].text()).toContain('No');
  });

  it('shows a placeholder when there are no users', () => {
    const wrapper = mount(UserTable, { props: { users: [] } });

    expect(wrapper.text()).toContain('No users imported yet.');
  });
});
