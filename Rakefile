require 'rake/clean'

task :default => [:clobber, :build]

CLOBBER.include('wright-membership-manager.zip')

desc "One line task description"
task :build do
  sh "zip -r wright-membership-manager.zip membership-manager"
end
