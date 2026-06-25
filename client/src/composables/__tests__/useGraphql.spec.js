import { describe, it, expect, vi, beforeEach } from 'vitest';
import { request, useMutation } from '../useGraphql';

describe('useGraphql', () => {
  beforeEach(() => {
    global.fetch = vi.fn();
  });

  it('posts the operation and variables and returns data', async () => {
    global.fetch.mockResolvedValue({
      json: () => Promise.resolve({ data: { users: [] } }),
    });

    const data = await request('query { users { id } }', { foo: 'bar' });

    expect(data).toEqual({ users: [] });
    const [url, options] = global.fetch.mock.calls[0];
    expect(url).toBe('/graphql');
    expect(options.method).toBe('POST');
    const body = JSON.parse(options.body);
    expect(body.query).toContain('users');
    expect(body.variables).toEqual({ foo: 'bar' });
  });

  it('throws when the response contains GraphQL errors', async () => {
    global.fetch.mockResolvedValue({
      json: () => Promise.resolve({ errors: [{ message: 'boom' }] }),
    });

    await expect(request('query { users { id } }')).rejects.toThrow('boom');
  });

  it('surfaces errors through useMutation state', async () => {
    global.fetch.mockResolvedValue({
      json: () => Promise.resolve({ errors: [{ message: 'nope' }] }),
    });

    const { mutate, error } = useMutation('mutation { x }');

    await expect(mutate()).rejects.toThrow('nope');
    expect(error.value.message).toBe('nope');
  });
});
