@servers(['web' => 'ofaasd@103.187.147.220'])

@task('deploy')
    sudo su
    cd /var/www/manajemen.ppatq-rf.id/public_html/
    git pull origin main
@endtask

@task('mixing')
  sudo su
  cd /var/www/manajemen.ppatq-rf.id/public_html/public/js
  rm *
  cd ../../
  npx mix
@endtask
