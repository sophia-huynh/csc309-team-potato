from django.db import models
import datetime
from django.utils import timezone

# character lengths have been hardcoded for now and arbitratily chosen
# this could be something an admin could modify
    
class User(models.Model):
    uid = models.AutoField(primary_key=True)
    name = models.CharField(max_length = 16)
    email = models.EmailField(max_length = 254)
    
    def __str__(self):
        return "{}: {}".format(self.uid, self.name)

class Project(models.Model):
    pid = models.AutoField(primary_key=True)
    name = models.CharField(max_length = 36)
    goal = models.IntegerField()
    funded = models.IntegerField()
    startdate = models.DateTimeField()
    enddate = models.DateTimeField()
    
    def has_ended(self):
        return timezone.now() > self.enddate
    
    def is_funded(self):
        return self.funded >= self.goal
    
    def __str__(self):
        return "{}: {}".format(self.pid, self.name)

class UserReview(models.Model):
    uid = models.ForeignKey('User')
    reviewer = models.ForeignKey('User')
    rid = models.ForeignKey('Rating')

class ProjectReview(models.Model):
    pid = models.ForeignKey('Project')
    uid = models.ForeignKey('User')
    rid = models.ForeignKey('Rating')

class Rating(models.Model):
    RATING_CHOICES = (
        (1, 'Poor'),
        (2, 'Below Average'),
        (3, 'Average'),
        (4, 'Above Average'),
        (5, 'Outstanding'),
        )
    rid = models.AutoField(primary_key = True)
    rating = models.IntegerField(choices = RATING_CHOICES, default = 3)
    comment = models.TextField(max_length = 500)
    
