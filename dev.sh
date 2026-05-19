#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "$0")"

env_file="../configs/env/local.env"
compose=(docker compose --env-file "$env_file" -f docker-compose.yml)
command="${1:-up}"

ensure_runtime_dirs() {
  mkdir -p log doc upload files voicecatalog img vipiska scheta mail/database
}

run_up() {
  ensure_runtime_dirs
  "${compose[@]}" up -d --build
}

restore_dump() {
  local dump_file="$1"

  if [[ ! -f "$dump_file" ]]; then
    echo "SQL dump not found: $dump_file" >&2
    exit 1
  fi

  set -a
  # shellcheck disable=SC1091
  . "$env_file"
  set +a

  : "${MYSQL_ROOT_PASSWORD:?MYSQL_ROOT_PASSWORD is not set in $env_file}"
  : "${MYSQL_DATABASE:=voovi}"

  ensure_runtime_dirs
  "${compose[@]}" up -d --build
  "${compose[@]}" exec -T db mysql -uroot -p"${MYSQL_ROOT_PASSWORD}" -e "DROP DATABASE IF EXISTS \`${MYSQL_DATABASE}\`; CREATE DATABASE \`${MYSQL_DATABASE}\` CHARACTER SET utf8 COLLATE utf8_general_ci;"
  "${compose[@]}" exec -T db mysql -uroot -p"${MYSQL_ROOT_PASSWORD}" "${MYSQL_DATABASE}" < "$dump_file"
}

case "$command" in
  up)
    run_up
    ;;
  help|-h|--help)
    echo "Usage:"
    echo "  ./dev.sh              build and start local dev"
    echo "  ./dev.sh up           build and start local dev"
    echo "  ./dev.sh voovi.sql    build, start, and fully restore local DB from dump"
    ;;
  *)
    restore_dump "$command"
    ;;
esac
