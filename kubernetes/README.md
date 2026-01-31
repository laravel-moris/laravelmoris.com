# Kubernetes Deployment for Laravel Moris


## Quick Deploy

```bash
# 1. Generate APP_KEY
php artisan key:generate --show

# 2. Update 04-secrets.yaml with APP_KEY

# 3. Deploy
kubectl apply -f kubernetes/

# 4. Verify
kubectl get pods -n laravel-moris
kubectl get ingress -n laravel-moris
```

## Setup Steps

1. **APP_KEY**: Add to `04-secrets.yaml` → `APP_KEY: "base64:YOUR_KEY"`
2. **Image tag**: Update `02-statefulset.yaml` with your image
3. **Domain**: Set domain in `05-ingress.yaml`
4. **TLS**: Ensure cert-manager ClusterIssuer exists

## Common Commands

```bash
# Restart pod
kubectl delete pod laravel-moris-0 -n laravel-moris

# View logs
kubectl logs -n laravel-moris statefulset/laravel-moris

# Check events
kubectl get events -n laravel-moris --sort-by='.lastTimestamp'
```

## Notes

- **SQLite limitation**: Keep replicas at 1. SQLite doesn't support concurrent writes.